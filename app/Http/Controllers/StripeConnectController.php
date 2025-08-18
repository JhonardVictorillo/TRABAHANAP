<?php

namespace App\Http\Controllers;

use App\Services\StripeConnectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeConnectController extends Controller
{
    protected $stripeConnectService;
    
    public function __construct(StripeConnectService $stripeConnectService)
    {
        $this->stripeConnectService = $stripeConnectService;
       
    }
    
    /**
     * Simple connect method for localhost development
     */
    public function connectAccount()
    {
        $user = auth()->user();
        
        if (!empty($user->stripe_connect_id)) {
            return redirect()->route('freelancer.dashboard')
                ->with('success', 'Your account is already connected to Stripe.');
        }
        
        $result = $this->stripeConnectService->createTestConnectedAccount($user);
        
        if ($result['success']) {
            return redirect()->route('freelancer.dashboard')
                ->with('success', 'Test Stripe account created successfully! You can now receive payments.');
        } else {
            return redirect()->route('freelancer.dashboard')
                ->with('error', 'Could not create Stripe account: ' . $result['message']);
        }
    }
    
    /**
     * Get account status
     */
    public function getStatus()
    {
        $user = auth()->user();
        
        if (empty($user->stripe_connect_id)) {
            return response()->json([
                'connected' => false,
                'message' => 'No Stripe account connected'
            ]);
        }
        
        try {
            $result = $this->stripeConnectService->getAccountDetails($user->stripe_connect_id);
            
            if ($result['success']) {
                $account = $result['account'];
                
                return response()->json([
                    'connected' => true,
                    'account_id' => $user->stripe_connect_id,
                    'account_type' => $account->type,
                    'email' => $account->email,
                    'charges_enabled' => $account->charges_enabled ?? false,
                    'payouts_enabled' => $account->payouts_enabled ?? false
                ]);
            } else {
                return response()->json([
                    'connected' => false,
                    'message' => $result['message']
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error getting account status', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'connected' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}