<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FreelancerPayoutController extends Controller
{
    private function setupStripe()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function store(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:100',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check if user has enough balance
        $user = auth()->user();
        $availableBalance = $this->getAvailableBalance($user->id);

        if ($request->amount > $availableBalance) {
            return redirect()->back()->with('error', 'Withdrawal amount exceeds your available balance')->withInput();
        }

        try {
            DB::beginTransaction();

            // Get payment details based on method
            $paymentDetails = $this->extractPaymentDetails($request);

            // Create withdrawal record
            $withdrawal = Withdrawal::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'payment_details' => $paymentDetails,
                'notes' => $request->notes
            ]);

            // Deduct amount from available balance
            DB::table('platform_revenues')->insert([
                'amount' => -$request->amount,
                'source' => 'withdrawal_request',
                'user_id' => $user->id,
                'date' => now()->format('Y-m-d'),
                'notes' => 'Withdrawal request #' . $withdrawal->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            // Notify admin about withdrawal request
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new WithdrawalRequestedNotification($withdrawal));
            }

            return redirect()->route('freelancer.dashboard')->with('success', 'Withdrawal request submitted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }

    private function extractPaymentDetails(Request $request)
    {
        $method = $request->payment_method;
        $details = [];

        switch ($method) {
            case 'bank_transfer':
                $details = [
                    'bank_name' => $request->bank_name === 'other' ? $request->other_bank : $request->bank_name,
                    'account_number' => $request->account_number,
                    'account_name' => $request->account_name
                ];
                break;
                
            case 'gcash':
                $details = [
                    'gcash_number' => $request->gcash_number,
                    'gcash_name' => $request->gcash_name
                ];
                break;
                
            case 'paymaya':
                $details = [
                    'paymaya_number' => $request->paymaya_number,
                    'paymaya_name' => $request->paymaya_name
                ];
                break;
                
            case 'grab_pay':
                $details = [
                    'grab_pay_number' => $request->grab_pay_number,
                    'grab_pay_name' => $request->grab_pay_name
                ];
                break;
        }

        return $details;
    }

    public function cancel($id)
    {
        try {
            DB::beginTransaction();

            // Find withdrawal request
            $withdrawal = Withdrawal::where('id', $id)
                ->where('user_id', auth()->id())
                ->where('status', 'pending')
                ->firstOrFail();

            // Return amount to available balance
            DB::table('platform_revenues')->insert([
                'amount' => $withdrawal->amount,
                'source' => 'withdrawal_cancelled',
                'user_id' => auth()->id(),
                'date' => now()->format('Y-m-d'),
                'notes' => 'Cancellation of withdrawal request #' . $withdrawal->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Update withdrawal status
            $withdrawal->status = 'cancelled';
            $withdrawal->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Withdrawal request cancelled successfully']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getAvailableBalance($userId)
    {
        // Calculate balance from platform_revenues
        $revenues = DB::table('platform_revenues')
            ->where('user_id', $userId)
            ->whereIn('source', [
                'final_payment_freelancer', 
                'commitment_fee_freelancer',
                'withdrawal_request',
                'withdrawal_cancelled',
                'no_show_fee',
                'late_cancellation_fee'
            ])
            ->sum('amount');

        return $revenues;
    }
}
