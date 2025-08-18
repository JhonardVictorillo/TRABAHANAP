<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Withdrawal;
use App\Models\User;
use App\Models\FreelancerEarning;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ServiceCompletionService
{
    protected $stripeConnectService;
    
    public function __construct(StripeConnectService $stripeConnectService)
    {
        $this->stripeConnectService = $stripeConnectService;
    }
    
    /**
     * Process automatic transfers for a completed service
     */
    public function processAutoTransfer(Appointment $appointment)
    {
        // Ensure the service is completed and paid
        if ($appointment->status !== 'completed' || $appointment->final_payment_status !== 'paid') {
            Log::info('Cannot process auto transfer - service not completed or paid', [
                'appointment_id' => $appointment->id,
                'status' => $appointment->status,
                'payment_status' => $appointment->final_payment_status
            ]);
            return false;
        }
        
        $freelancer = User::find($appointment->freelancer_id);
        
        // Check if freelancer has Stripe Connect and auto-transfers enabled
        if (empty($freelancer->stripe_connect_id)) {
            Log::info('Freelancer does not have Stripe Connect account - skipping auto transfer', [
                'freelancer_id' => $freelancer->id,
                'appointment_id' => $appointment->id
            ]);
            return false;
        }
        
        // Check if auto-transfer is enabled (you'll need to add this column)
        if (!$freelancer->auto_transfer_enabled) {
            Log::info('Freelancer has disabled auto transfers', [
                'freelancer_id' => $freelancer->id,
                'appointment_id' => $appointment->id
            ]);
            return false;
        }
        
        // Check if a transfer has already been created for this appointment
        $existingWithdrawal = Withdrawal::where('appointment_id', $appointment->id)
            ->where('is_automatic', true)
            ->first();
            
        if ($existingWithdrawal) {
            Log::info('Auto transfer already processed for this appointment', [
                'appointment_id' => $appointment->id,
                'withdrawal_id' => $existingWithdrawal->id
            ]);
            return false;
        }
        
        // Calculate available earnings for this appointment
        $earnings = FreelancerEarning::where('appointment_id', $appointment->id)
            ->where('freelancer_id', $freelancer->id)
            ->sum('amount');
            
        if ($earnings <= 0) {
            Log::warning('No earnings found for appointment', [
                'appointment_id' => $appointment->id,
                'freelancer_id' => $freelancer->id
            ]);
            return false;
        }
        
        try {
            DB::beginTransaction();
            
            // Create a withdrawal record
            $withdrawal = new Withdrawal();
            $withdrawal->user_id = $freelancer->id;
            $withdrawal->appointment_id = $appointment->id; // Add this column to withdrawals table
            $withdrawal->amount = $earnings;
            $withdrawal->status = 'pending';
            $withdrawal->payment_method = 'stripe';
            $withdrawal->is_automatic = true;
            $withdrawal->notes = 'Automatic withdrawal for completed service #' . $appointment->id;
            $withdrawal->reference = 'AUTO-' . time() . '-' . $appointment->id;
            
            // Add payment details
            $withdrawal->payment_details = json_encode([
                'method_type' => 'stripe_connect',
                'account_id' => $freelancer->stripe_connect_id,
                'appointment_id' => $appointment->id,
                'timestamp' => now()->timestamp
            ]);
            
            $withdrawal->save();
            
            Log::info('Created automatic withdrawal record', [
                'withdrawal_id' => $withdrawal->id,
                'freelancer_id' => $freelancer->id,
                'amount' => $earnings,
                'appointment_id' => $appointment->id
            ]);
            
            // Process the withdrawal via Stripe
            $result = $this->stripeConnectService->processWithdrawal($withdrawal);
            
            if (!$result['success']) {
                throw new \Exception('Failed to process Stripe transfer: ' . $result['message']);
            }
            
            // Update withdrawal with Stripe details
            $withdrawal->stripe_transfer_id = $result['transfer_id'];
            $withdrawal->status = 'processing';
            $withdrawal->save();
            
            // Update appointment to mark as transferred
            $appointment->earnings_transferred = true;
            $appointment->save();
            
            DB::commit();
            
            Log::info('Automatic Stripe transfer processed successfully', [
                'withdrawal_id' => $withdrawal->id,
                'transfer_id' => $result['transfer_id'],
                'appointment_id' => $appointment->id
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error processing automatic Stripe transfer', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return false;
        }
    }
}