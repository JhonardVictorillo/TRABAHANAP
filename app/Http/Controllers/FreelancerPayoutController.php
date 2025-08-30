<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Withdrawal;
use App\Services\StripeConnectService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use App\Notifications\WithdrawalStatusNotification;

class FreelancerPayoutController extends Controller
{
    protected $stripeConnectService;

    public function __construct(StripeConnectService $stripeConnectService)
    {
        $this->stripeConnectService = $stripeConnectService;
       
    }

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

        // Add debugging
        Log::info('Withdrawal comparison', [
            'requested_amount' => (float)$request->amount,
            'available_balance' => (float)$availableBalance,
            'comparison' => (float)$request->amount > (float)$availableBalance,
            'raw_requested' => $request->amount,
            'raw_balance' => $availableBalance,
            'types' => [
                'requested' => gettype($request->amount),
                'balance' => gettype($availableBalance)
            ]
        ]);

        if ($request->amount > $availableBalance) {
            return redirect()->back()->with('error', 'Withdrawal amount exceeds your available balance')->withInput();
        }

        try {
            DB::beginTransaction();

            // Get payment details based on method
            $paymentDetails = $this->extractPaymentDetails($request);

            // Create withdrawal record
            $withdrawal = Withdrawal::create([
                'user_id' => $user->id, // Assuming your column is 'freelancer_id' not 'user_id'
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
               'payment_details' => json_encode($paymentDetails), 
                'notes' => $request->notes
            ]);

            // Handle Stripe Connect withdrawals immediately
            if ($request->payment_method === 'stripe') {
                Log::info('Processing Stripe Connect withdrawal', [
                    'withdrawal_id' => $withdrawal->id,
                    'user_id' => $user->id
                ]);
                
                // Process the withdrawal via Stripe Connect
                $stripeResult = $this->stripeConnectService->processWithdrawal($withdrawal);
                
                if (!$stripeResult['success']) {
                    // Instead of throwing an exception, mark as failed and continue
                    $withdrawal->status = 'failed';
                    $withdrawal->admin_notes = 'Stripe Connect processing failed: ' . $stripeResult['message'];
                    $withdrawal->save();
                  DB::commit(); // Still commit the transaction to record the attempt
        
                    Log::error('Stripe Connect withdrawal failed', [
                        'withdrawal_id' => $withdrawal->id,
                        'error' => $stripeResult['message']
                    ]);
                    
                    return redirect()->back()->with('error', 'Failed to process withdrawal: ' . $stripeResult['message'])->withInput();
                }

                 // Update the withdrawal with Stripe details
                    $withdrawal->stripe_transfer_id = $stripeResult['transfer_id'];
                    $withdrawal->status = 'processing'; // Mark as processing, not completed yet
                    $withdrawal->save();
                    
                    Log::info('Stripe Connect withdrawal initiated successfully', [
                        'withdrawal_id' => $withdrawal->id,
                        'transfer_id' => $stripeResult['transfer_id']
                    ]);
                }
    

            // Deduct amount from available balance
              
              if ($withdrawal->status === 'pending') {
                DB::table('freelancer_earnings')->insert([
                    'amount' => -$request->amount,
                    'source' => 'withdrawal_request',
                    'freelancer_id' => $user->id,
                    'date' => now()->format('Y-m-d'),
                    'notes' => 'Withdrawal request #' . $withdrawal->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();

            // Notify admin about withdrawal request (except for Stripe which is automatic)
            if ($request->payment_method !== 'stripe') {
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new WithdrawalStatusNotification($withdrawal));
                }
            }

            // Also notify the freelancer that their request was received
            $user->notify(new WithdrawalStatusNotification($withdrawal));
            // Customize success message based on payment method
            $successMessage = $request->payment_method === 'stripe'
               ? 'Your withdrawal has been initiated via Stripe Connect and is being processed!'
                 : 'Withdrawal request submitted successfully!';

            return redirect()->route('freelancer.dashboard')->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Withdrawal processing error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }

      private function extractPaymentDetails(Request $request)
    {
        $method = $request->payment_method;
        $details = [];

        switch ($method) {
            case 'stripe':
                $details = [
                   'method_type' => 'stripe_connect',
                    'account_id' => auth()->user()->stripe_connect_id,
                    'timestamp' => now()->timestamp
                ];
                break;
                
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
                ->where('user_id', auth()->id()) // Assuming your column is 'user_id'
                ->where('status', 'pending')
                ->firstOrFail();

            // Return amount to available balance
            DB::table('freelancer_earnings')->insert([
                'amount' => $withdrawal->amount,
                'source' => 'withdrawal_cancelled',
                'freelancer_id' => auth()->id(),
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
            Log::error('Withdrawal cancellation error', [
                'message' => $e->getMessage(),
                'withdrawal_id' => $id
            ]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getAvailableBalance($userId)
    {
        // Calculate balance from freelancer_earnings
      $allEntries = DB::table('freelancer_earnings')
        ->where('freelancer_id', $userId)
        ->select('source', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
        ->groupBy('source')
        ->get();
    
    Log::info('All earnings entries by source', [
        'user_id' => $userId,
        'sources' => $allEntries
    ]);
 $revenues = DB::table('freelancer_earnings')
        ->where('freelancer_id', $userId)
        ->whereIn('source', [
            'service_payment', // Changed from 'final_payment_freelancer'
            'commitment_fee', // Changed from 'commitment_fee_freelancer'
            'withdrawal_request',
            'withdrawal_cancelled',
            'no_show_fee',
            'late_cancellation_fee'
            // Add any other sources that should be included
        ])
        ->sum('amount');

    Log::info('Calculated balance', [
        'user_id' => $userId,
        'balance' => $revenues
    ]);

    return $revenues;
    }


    public function getDetails($id)
{
    try {
        $withdrawal = Withdrawal::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();
            
        if (!$withdrawal) {
            return response()->json([
                'success' => false,
                'message' => 'Withdrawal not found'
            ], 404);
        }
        
        // Handle payment details - ensure they're properly formatted
        if (is_string($withdrawal->payment_details) && !empty($withdrawal->payment_details)) {
            $withdrawal->payment_details = json_decode($withdrawal->payment_details, true);
        }
        
        return response()->json([
            'success' => true,
            'withdrawal' => $withdrawal
        ]);
    } catch (\Exception $e) {
        Log::error('Error fetching withdrawal details', [
            'withdrawal_id' => $id,
            'error' => $e->getMessage()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Error fetching withdrawal details'
        ], 500);
    }
}
}