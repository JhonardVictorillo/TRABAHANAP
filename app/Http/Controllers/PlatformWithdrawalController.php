<?php

namespace App\Http\Controllers;

use App\Models\PlatformRevenue;
use App\Models\PlatformWithdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Payout;
use Exception;

class PlatformWithdrawalController extends Controller
{
  public function store(Request $request)
{
    $rules = [
        'amount' => 'required|numeric|min:1',
        'payment_method' => 'required|string|max:255',
        'notes' => 'nullable|string',
        'reference_number' => 'nullable|string|max:255' // Add this line
    ];

    // Only require bank info for manual transfers
    if ($request->payment_method === 'bank_transfer') {
        $rules['bank_name'] = 'required|string|max:255';
        $rules['account_number'] = 'required|string|max:255';
    } else {
        $rules['bank_name'] = 'nullable|string|max:255';
        $rules['account_number'] = 'nullable|string|max:255';
    }
    
    $request->validate($rules);
    
    // Calculate available revenue
    $totalRevenue = PlatformRevenue::sum('amount');
    $withdrawnRevenue = PlatformWithdrawal::where('status', '!=', 'rejected')->sum('amount');
    $availableRevenue = $totalRevenue - $withdrawnRevenue;
    
    // Check if withdrawal amount is valid
    if ($request->amount > $availableRevenue) {
        return redirect()
            ->back()
            ->with('error', 'Withdrawal amount exceeds available revenue.');
    }
    
    try {
        // Debug to see what's happening
        Log::info('Starting platform withdrawal process', [
            'request_data' => $request->all(),
            'available_revenue' => $availableRevenue
        ]);
        
        DB::beginTransaction();
        
        // Create withdrawal data properly
        $withdrawalData = [
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
            'admin_id' => auth()->id(),
            'status' => 'processing'
        ];
        
        // Set bank fields based on payment method
        if ($request->payment_method === 'stripe') {
            $withdrawalData['bank_name'] = 'Stripe Direct';
            $withdrawalData['account_number'] = 'N/A';
            $withdrawalData['reference_number'] = 'STRIPE-' . time();
        } else {
            $withdrawalData['bank_name'] = $request->bank_name;
            $withdrawalData['account_number'] = $request->account_number;
            $withdrawalData['reference_number'] = $request->reference_number;
        }
        
        // Create withdrawal record without explicitly setting created_at
        $withdrawal = PlatformWithdrawal::create($withdrawalData);
        
        // Process Stripe payout if payment method is Stripe
        if ($request->payment_method === 'stripe') {
            $result = $this->createStripePayout($withdrawal);
            
            if (!$result['success']) {
                throw new Exception($result['message']);
            }
            
            // Update withdrawal with Stripe payout ID
            $withdrawal->stripe_payout_id = $result['payout_id'];
            $withdrawal->save();
        }
        
        DB::commit();
        
        Log::info('Platform withdrawal created', [
            'admin_id' => auth()->id(),
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'withdrawal_id' => $withdrawal->id
        ]);
        
        return redirect()
            ->route('admin.dashboard', ['section' => 'revenue', 'tab' => 'withdrawals'])
            ->with('success', 'Platform revenue withdrawal initiated successfully.');
            
    } catch (\Exception $e) {
        DB::rollback();
        
        Log::error('Platform withdrawal error', [
            'admin_id' => auth()->id(),
            'amount' => $request->amount,
            'error_message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()
            ->back()
            ->with('error', 'Error processing platform withdrawal: ' . $e->getMessage());
    }
}
    
    /**
     * Create a Stripe payout for the platform
     */
 protected function createStripePayout($withdrawal)
{
    try {
        // Set your Stripe API key
        Stripe::setApiKey(config('services.stripe.secret'));
        
        // Amount in cents (for Stripe)
        $amountInCents = $withdrawal->amount * 100;
        
        // Log before creating payout
        Log::info('Attempting to create Stripe payout', [
            'withdrawal_id' => $withdrawal->id,
            'amount_cents' => $amountInCents
        ]);
        
        // Check environment
        if (config('app.env') === 'production') {
            // PRODUCTION MODE - Real payout
            $payout = Payout::create([
                'amount' => $amountInCents,
                'currency' => 'usd', // Change to USD for testing or whatever currency is supported
                'description' => 'Platform revenue withdrawal #' . $withdrawal->id,
                'metadata' => [
                    'withdrawal_id' => $withdrawal->id,
                    'admin_id' => auth()->id()
                ]
            ]);
            
            $payoutId = $payout->id;
        } else {
            // DEVELOPMENT/TEST MODE - Skip actual API call and simulate success
            $payoutId = 'test_po_' . time() . '_' . rand(1000, 9999);
            Log::info('Development mode: Simulated Stripe payout', [
                'withdrawal_id' => $withdrawal->id,
                'fake_payout_id' => $payoutId
            ]);
        }
        
        return [
            'success' => true,
            'payout_id' => $payoutId,
            'message' => 'Stripe payout created successfully'
        ];
        
    } catch (\Exception $e) {
        Log::error('Stripe payout error', [
            'withdrawal_id' => $withdrawal->id,
            'error_message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return [
            'success' => false,
            'message' => 'Error creating Stripe payout: ' . $e->getMessage()
        ];
    }
}
    
    /**
     * Complete a platform withdrawal (mark as completed)
     */
   public function complete($id)
{
    try {
        $withdrawal = PlatformWithdrawal::findOrFail($id);
        
        // Only processing withdrawals can be completed
        if ($withdrawal->status !== 'processing') {
            return response()->json([
                'success' => false,
                'message' => 'Only processing withdrawals can be marked as completed.'
            ], 400);
        }

        DB::beginTransaction();
        
        // Update withdrawal status
        $withdrawal->status = 'completed';
        $withdrawal->processed_at = now(); // This will save as a proper DateTime
        $withdrawal->admin_notes = ($withdrawal->admin_notes ? $withdrawal->admin_notes . "\n" : '') .
            "Marked as completed by admin on " . now()->format('Y-m-d H:i:s');
        $withdrawal->save();
        
        // Create a withdrawal transaction record in platform_revenue table with negative amount
        // This ensures the withdrawal is properly accounted for in the balance calculation
        PlatformRevenue::create([
            'amount' => -$withdrawal->amount, // Negative amount to reduce balance
            'date' => now(),
            'source' => 'platform_withdrawal',
            'notes' => 'Platform withdrawal #' . $withdrawal->id,
            'admin_id' => auth()->id()
        ]);
        
        DB::commit();
        
        Log::info('Platform withdrawal completed', [
            'withdrawal_id' => $withdrawal->id,
            'admin_id' => auth()->id(),
            'amount_deducted' => $withdrawal->amount
        ]);
        
        return redirect()->back()->with('success', 'Platform withdrawal #' . $withdrawal->id . ' has been marked as completed!');
        
    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('Error completing platform withdrawal', [
            'withdrawal_id' => $id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
       return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}
    
    /**
     * Display list of platform withdrawals
     */
    public function index()
    {
        $withdrawals = PlatformWithdrawal::with('admin')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $totalRevenue = PlatformRevenue::sum('amount');
        $withdrawnRevenue = PlatformWithdrawal::sum('amount');
        $availableRevenue = $totalRevenue - $withdrawnRevenue;
            
        return view('admin.platform-withdrawals.index', compact('withdrawals', 'availableRevenue'));
    }
}
