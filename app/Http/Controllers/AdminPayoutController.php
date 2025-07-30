<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminPayoutController extends Controller
{
   private function setupStripe()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }
    
    public function index()
    {
        $withdrawals = Withdrawal::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.withdrawals.index', compact('withdrawals'));
    }
    
    public function show($id)
    {
        $withdrawal = Withdrawal::with('user')->findOrFail($id);
        return view('admin.withdrawals.show', compact('withdrawal'));
    }
    
    public function process(Request $request, $id)
    {
        $withdrawal = Withdrawal::with('user')->findOrFail($id);
        
        if ($withdrawal->status !== 'pending') {
            return redirect()->back()->with('error', 'This withdrawal request has already been processed');
        }
        
        // Mark as processing
        $withdrawal->status = 'processing';
        $withdrawal->save();
        
        try {
            $this->setupStripe();
            
            // For test mode, we'll simulate a payout
            // In production, you'd use the user's connected Stripe account or bank details
            
            // Option 1: Using Stripe Payout API (requires connected account)
            /*
            $payout = Payout::create([
                'amount' => $withdrawal->amount * 100, // Convert to cents
                'currency' => 'php',
                'description' => 'Payout for withdrawal #' . $withdrawal->id,
                'statement_descriptor' => 'WHUB PAYOUT',
            ]);
            
            $withdrawal->stripe_payout_id = $payout->id;
            */
            
            // Option 2: Manual payout tracking (more flexible for test mode)
            $withdrawal->stripe_payout_id = 'manual_' . uniqid();
            $withdrawal->status = 'completed';
            $withdrawal->processed_at = now();
            $withdrawal->admin_notes = $request->admin_notes ?? 'Processed manually';
            $withdrawal->save();
            
            // Notify user
            $withdrawal->user->notify(new WithdrawalProcessedNotification($withdrawal));
            
            return redirect()->route('admin.withdrawals.index')
                ->with('success', 'Withdrawal request processed successfully');
                
        } catch (\Exception $e) {
            Log::error('Payout processing error', [
                'withdrawal_id' => $withdrawal->id,
                'error' => $e->getMessage()
            ]);
            
            // Reset status
            $withdrawal->status = 'pending';
            $withdrawal->save();
            
            return redirect()->back()
                ->with('error', 'Error processing payout: ' . $e->getMessage());
        }
    }
    
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255'
        ]);
        
        $withdrawal = Withdrawal::with('user')->findOrFail($id);
        
        if ($withdrawal->status !== 'pending') {
            return redirect()->back()->with('error', 'This withdrawal request has already been processed');
        }
        
        try {
            // Return funds to user's balance
            DB::table('platform_revenues')->insert([
                'amount' => $withdrawal->amount,
                'source' => 'withdrawal_rejected',
                'user_id' => $withdrawal->user_id,
                'date' => now()->format('Y-m-d'),
                'notes' => 'Rejection of withdrawal #' . $withdrawal->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Update withdrawal status
            $withdrawal->status = 'rejected';
            $withdrawal->admin_notes = $request->rejection_reason;
            $withdrawal->processed_at = now();
            $withdrawal->save();
            
            // Notify user
            $withdrawal->user->notify(new WithdrawalRejectedNotification($withdrawal));
            
            return redirect()->route('admin.withdrawals.index')
                ->with('success', 'Withdrawal request rejected successfully');
                
        } catch (\Exception $e) {
            Log::error('Withdrawal rejection error', [
                'withdrawal_id' => $withdrawal->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Error rejecting withdrawal: ' . $e->getMessage());
        }
    }
}
