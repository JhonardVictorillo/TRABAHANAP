<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Transfer;
use Stripe\Account;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StripeConnectService
{
    public function __construct()
    {
        // Use the same Stripe API key you already have configured
        Stripe::setApiKey(config('services.stripe.secret'));
    }
    
    /**
     * Create a test connected account for a freelancer
     * This simplified version works better for localhost development
     */
    public function createTestConnectedAccount(User $freelancer)
    {
        try {
            // Log the attempt
            Log::info('Creating Stripe Connect account', [
                'freelancer_id' => $freelancer->id,
            ]);
            
            // Create a minimal test Stripe account - much simpler for sandbox testing
            $account = \Stripe\Account::create([
                'type' => 'standard', // Use standard instead of express/custom
                'country' => 'US',
                'email' => $freelancer->email
            ]);
            
            Log::info('Created Stripe Connect account', [
                'freelancer_id' => $freelancer->id,
                'stripe_account_id' => $account->id
            ]);
            
            // Store the account ID in your database
            $freelancer->stripe_connect_id = $account->id;
            $freelancer->save();
            
            return [
                'success' => true,
                'account_id' => $account->id,
                'message' => 'Test connected account created successfully'
            ];
        } catch (\Exception $e) {
            Log::error('Error creating Stripe test account', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $freelancer->id
            ]);
            
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Process a withdrawal via Stripe Transfer
     */
    public function processWithdrawal(Withdrawal $withdrawal)
    {
        try {
            $freelancer = User::find($withdrawal->user_id);
            
            if (!$freelancer) {
                Log::error('Freelancer not found', [
                    'withdrawal_id' => $withdrawal->id,
                    'user_id' => $withdrawal->user_id
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Freelancer not found'
                ];
            }
            
            // Make sure freelancer has a Stripe Connect ID
            if (empty($freelancer->stripe_connect_id)) {
                return [
                    'success' => false,
                    'message' => 'Freelancer does not have a connected Stripe account. Please ask them to connect their account first.'
                ];
            }
            
            // Check if account is ready to receive payments
            try {
                $accountDetails = $this->getAccountDetails($freelancer->stripe_connect_id);
                
                // For standard accounts, this check might not be needed
                // Remove this check if it causes issues with standard accounts
                if (!$accountDetails['success'] || 
                    (isset($accountDetails['account']->capabilities->transfers) && 
                    $accountDetails['account']->capabilities->transfers !== 'active')) {
                    
                    return [
                        'success' => false,
                        'message' => 'The connected account is not fully verified for transfers'
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Error checking account capabilities', [
                    'user_id' => $freelancer->id,
                    'error' => $e->getMessage()
                ]);
            }
            
            // Convert amount to cents (Stripe requires amounts in cents)
            $amountInCents = intval($withdrawal->amount * 100);
            
            Log::info('Processing Stripe Transfer', [
                'withdrawal_id' => $withdrawal->id,
                'amount' => $withdrawal->amount,
                'amount_cents' => $amountInCents,
                'stripe_account' => $freelancer->stripe_connect_id
            ]);
            
            // Create a transfer to the connected account
            $transfer = Transfer::create([
                'amount' => $amountInCents,
                'currency' => 'usd',
                'destination' => $freelancer->stripe_connect_id,
                'transfer_group' => 'WITHDRAWAL-' . $withdrawal->id,
                 'metadata' => [
                    'withdrawal_id' => $withdrawal->id,
                    'freelancer_id' => $freelancer->id,
                    'freelancer_email' => $freelancer->email
                ]
            ]);
            
            Log::info('Stripe Transfer created successfully', [
                'withdrawal_id' => $withdrawal->id,
                'transfer_id' => $transfer->id
            ]);
            
            // Update withdrawal record
            $withdrawal->status = 'processing';  // Changed from 'completed'
            $withdrawal->stripe_transfer_id = $transfer->id;  // Store the transfer ID
            $withdrawal->admin_notes = ($withdrawal->admin_notes ? $withdrawal->admin_notes . "\n" : '') . 
                "Stripe Transfer initiated. ID: {$transfer->id}";
            $withdrawal->save();
            
            return [
                'success' => true,
                'transfer_id' => $transfer->id,
                'message' => 'Withdrawal processed successfully'
            ];
        } catch (\Exception $e) {
            Log::error('Stripe Transfer Error', [
                'withdrawal_id' => $withdrawal->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Update withdrawal to failed status
            $withdrawal->status = 'failed';
            $withdrawal->admin_notes = ($withdrawal->admin_notes ? $withdrawal->admin_notes . "\n" : '') . 
                "Stripe Transfer failed: " . $e->getMessage();
            $withdrawal->save();
            
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get account details for a connected account
     */
    public function getAccountDetails($accountId)
    {
        try {
            $account = Account::retrieve($accountId);
            return [
                'success' => true,
                'account' => $account
            ];
        } catch (\Exception $e) {
            Log::error('Error retrieving Stripe account', [
                'account_id' => $accountId,
                'message' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * List transfers for a connected account
     */
    public function listTransfers($accountId)
    {
        try {
            $transfers = Transfer::all([
                'destination' => $accountId,
                'limit' => 10
            ]);
            
            return [
                'success' => true,
                'transfers' => $transfers->data
            ];
        } catch (\Exception $e) {
            Log::error('Error listing transfers', [
                'account_id' => $accountId,
                'message' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
}