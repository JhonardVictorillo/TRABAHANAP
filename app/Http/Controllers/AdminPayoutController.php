<?php

// filepath: c:\xampp\htdocs\wHUB\app\Http\Controllers\AdminPayoutController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Withdrawal;
use App\Models\User;
use App\Services\StripeConnectService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\WithdrawalStatusNotification;

class AdminPayoutController extends Controller
{
    protected $stripeConnectService;
    
    public function __construct(StripeConnectService $stripeConnectService)
    {
        $this->stripeConnectService = $stripeConnectService;
    }
    
    /**
     * Display a listing of all withdrawal requests
     */
    public function index(Request $request)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }
        
        // Get status filter
        $status = $request->get('status', 'all');
        
        // Build query
        $query = Withdrawal::with('freelancer');
        
        // Apply status filter if not 'all'
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Order by status (pending first) and then by created date (newest first)
        $withdrawals = $query->orderByRaw("
            CASE 
                WHEN status = 'pending' THEN 1 
                WHEN status = 'processing' THEN 2
                ELSE 3 
            END
        ")->orderBy('created_at', 'desc')->paginate(10);
        
        // Get statistics for dashboard
        $stats = [
            'pending_count' => Withdrawal::where('status', 'pending')->count(),
            'processing_count' => Withdrawal::where('status', 'processing')->count(),
            'completed_count' => Withdrawal::where('status', 'completed')->count(),
            'rejected_count' => Withdrawal::where('status', 'rejected')->count(),
            'total_pending_amount' => Withdrawal::where('status', 'pending')->sum('amount'),
            'total_processing_amount' => Withdrawal::where('status', 'processing')->sum('amount'),
            'total_completed_amount' => Withdrawal::where('status', 'completed')->sum('amount'),
        ];
        
        return view('admin.withdrawals.index', compact('withdrawals', 'status', 'stats'));
    }
    
    /**
     * Show a specific withdrawal request
     */
    public function show($id)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }
        
        $withdrawal = Withdrawal::with('freelancer')->findOrFail($id);
        
        // Get payment method details
        $methodDetails = $this->getPaymentMethodDetails($withdrawal);
        
        // Get freelancer balance
        $balance = $this->getFreelancerBalance($withdrawal->freelancer_id);
        
        // Check if this is a Stripe withdrawal and if the freelancer has a Stripe account
        $hasStripeAccount = false;
        $stripeAccountDetails = null;
        
        if ($withdrawal->payment_method == 'stripe' && !empty($withdrawal->freelancer->stripe_connect_id)) {
            $hasStripeAccount = true;
            
            // Try to get Stripe account details
            try {
                $accountDetails = $this->stripeConnectService->getAccountDetails($withdrawal->freelancer->stripe_connect_id);
                if ($accountDetails['success']) {
                    $stripeAccountDetails = $accountDetails['account'];
                }
            } catch (\Exception $e) {
                Log::error('Error fetching Stripe account details', [
                    'stripe_account_id' => $withdrawal->freelancer->stripe_connect_id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return view('admin.withdrawals.show', compact(
            'withdrawal', 
            'methodDetails',
            'balance',
            'hasStripeAccount',
            'stripeAccountDetails'
        ));
    }
    
    /**
     * Process a withdrawal
     */
    public function process(Request $request, $id)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
        }
        
        // Validate request
        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);
        
        $withdrawal = Withdrawal::findOrFail($id);
        
        // Only allow processing of pending withdrawals
        if ($withdrawal->status !== 'pending') {
            return redirect()->back()->with('error', 'This withdrawal has already been processed.');
        }
        
        try {
            DB::beginTransaction();
            
            // For Stripe Connect withdrawals, process through Stripe
            if ($withdrawal->payment_method == 'stripe') {
                $result = $this->stripeConnectService->processWithdrawal($withdrawal);
                
                if (!$result['success']) {
                    throw new \Exception('Stripe error: ' . $result['message']);
                }
                
                // Add admin notes
                $notes = $request->admin_notes ? $request->admin_notes : '';
                $notes .= "\nProcessed via Stripe Connect. Transfer ID: " . $result['transfer_id'];
                
                $withdrawal->admin_notes = $notes;
                $withdrawal->status = 'completed';
                $withdrawal->processed_at = now();
                
                Log::info('Stripe withdrawal processed by admin', [
                    'withdrawal_id' => $withdrawal->id,
                    'transfer_id' => $result['transfer_id'],
                    'admin_id' => auth()->id()
                ]);
            } else {
                // For other payment methods, just update status
                $withdrawal->status = 'processing';
                $withdrawal->admin_notes = $request->admin_notes;
                
                Log::info('Withdrawal marked as processing by admin', [
                    'withdrawal_id' => $withdrawal->id,
                    'payment_method' => $withdrawal->payment_method,
                    'admin_id' => auth()->id()
                ]);
            }
            
            $withdrawal->save();
            
            // Notify the freelancer about the withdrawal status change
            $withdrawal->freelancer->notify(new WithdrawalStatusNotification($withdrawal));
            
            DB::commit();
            
            // Success message based on payment method
            $message = $withdrawal->payment_method == 'stripe'
                ? 'Withdrawal has been processed successfully through Stripe Connect.'
                : 'Withdrawal has been marked as processing. Please complete the manual transfer.';
                
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error processing withdrawal', [
                'withdrawal_id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Error processing withdrawal: ' . $e->getMessage());
        }
    }
    
    /**
     * Mark withdrawal as completed (for non-Stripe methods)
     */
    public function complete(Request $request, $id)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }
        
        // Validate request
        $request->validate([
            'transaction_reference' => 'required|string|max:100',
            'admin_notes' => 'nullable|string|max:500',
        ]);
        
        $withdrawal = Withdrawal::findOrFail($id);
        
        // Only allow completion of processing withdrawals
        if ($withdrawal->status !== 'processing') {
            return redirect()->back()->with('error', 'Only processing withdrawals can be marked as completed.');
        }
        
        try {
            DB::beginTransaction();
            
            // Update withdrawal
            $withdrawal->status = 'completed';
            $withdrawal->processed_at = now();
            
            // Add transaction reference to admin notes
            $notes = $withdrawal->admin_notes ? $withdrawal->admin_notes . "\n" : '';
            $notes .= "Transaction Reference: " . $request->transaction_reference;
            
            if ($request->admin_notes) {
                $notes .= "\n" . $request->admin_notes;
            }
            
            $withdrawal->admin_notes = $notes;
            $withdrawal->save();
            
            // Notify the freelancer
            $withdrawal->freelancer->notify(new WithdrawalStatusNotification($withdrawal));
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Withdrawal marked as completed successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error completing withdrawal', [
                'withdrawal_id' => $id,
                'message' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Error completing withdrawal: ' . $e->getMessage());
        }
    }
    
    /**
     * Reject a withdrawal request
     */
    public function reject(Request $request, $id)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }
        
        // Validate request
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);
        
        $withdrawal = Withdrawal::findOrFail($id);
        
        // Only allow rejection of pending or processing withdrawals
        if (!in_array($withdrawal->status, ['pending', 'processing'])) {
            return redirect()->back()->with('error', 'Only pending or processing withdrawals can be rejected.');
        }
        
        try {
            DB::beginTransaction();
            
            // Record the original amount and status
            $originalAmount = $withdrawal->amount;
            $originalStatus = $withdrawal->status;
            
            // Update withdrawal
            $withdrawal->status = 'rejected';
            $withdrawal->admin_notes = "Rejected: " . $request->rejection_reason;
            $withdrawal->save();
            
            // Return funds to freelancer's available balance
            DB::table('freelancer_earnings')->insert([
                'amount' => $originalAmount,
                'source' => 'withdrawal_rejected',
                'freelancer_id' => $withdrawal->freelancer_id,
                'date' => now()->format('Y-m-d'),
                'notes' => 'Returned funds from rejected withdrawal #' . $withdrawal->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Notify the freelancer
            $withdrawal->freelancer->notify(new WithdrawalStatusNotification($withdrawal));
            
            DB::commit();
            
            Log::info('Withdrawal rejected by admin', [
                'withdrawal_id' => $id,
                'admin_id' => auth()->id(),
                'original_status' => $originalStatus,
                'amount_returned' => $originalAmount
            ]);
            
            return redirect()->back()->with('success', 'Withdrawal rejected successfully. Funds have been returned to the freelancer\'s balance.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error rejecting withdrawal', [
                'withdrawal_id' => $id,
                'message' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Error rejecting withdrawal: ' . $e->getMessage());
        }
    }
    
    /**
     * Get payment method details for display
     */
    private function getPaymentMethodDetails($withdrawal)
    {
        $details = [];
        
        switch ($withdrawal->payment_method) {
            case 'stripe':
                $details = [
                    'method' => 'Stripe Connect (Instant)',
                    'account' => $withdrawal->freelancer->stripe_connect_id ?? 'Not connected',
                ];
                break;
                
            case 'bank_transfer':
                $paymentDetails = $withdrawal->payment_details;
                $details = [
                    'method' => 'Bank Transfer',
                    'bank_name' => $paymentDetails['bank_name'] ?? 'N/A',
                    'account_number' => $paymentDetails['account_number'] ?? 'N/A',
                    'account_name' => $paymentDetails['account_name'] ?? 'N/A',
                ];
                break;
                
            case 'gcash':
                $paymentDetails = $withdrawal->payment_details;
                $details = [
                    'method' => 'GCash',
                    'gcash_number' => $paymentDetails['gcash_number'] ?? 'N/A',
                    'gcash_name' => $paymentDetails['gcash_name'] ?? 'N/A',
                ];
                break;
                
            case 'paymaya':
                $paymentDetails = $withdrawal->payment_details;
                $details = [
                    'method' => 'PayMaya',
                    'paymaya_number' => $paymentDetails['paymaya_number'] ?? 'N/A',
                    'paymaya_name' => $paymentDetails['paymaya_name'] ?? 'N/A',
                ];
                break;
                
            case 'grab_pay':
                $paymentDetails = $withdrawal->payment_details;
                $details = [
                    'method' => 'GrabPay',
                    'grab_pay_number' => $paymentDetails['grab_pay_number'] ?? 'N/A',
                    'grab_pay_name' => $paymentDetails['grab_pay_name'] ?? 'N/A',
                ];
                break;
                
            default:
                $details = [
                    'method' => ucfirst($withdrawal->payment_method),
                    'details' => 'No additional details available',
                ];
        }
        
        return $details;
    }
    
    /**
     * Get freelancer's available balance
     */
    private function getFreelancerBalance($freelancerId)
    {
        // Calculate earnings from freelancer_earnings
        $earnings = DB::table('freelancer_earnings')
            ->where('freelancer_id', $freelancerId)
            ->sum('amount');
            
        // Calculate withdrawals
        $withdrawals = DB::table('withdrawals')
            ->where('user_id', $freelancerId)
            ->whereIn('status', ['pending', 'processing', 'completed'])
            ->sum('amount');
            
        return $earnings - $withdrawals;
    }


   public function getDetails($id)
{
    // Check if user is admin
    if (auth()->user()->role !== 'admin') {
        return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
    }
    
    try {
        $withdrawal = Withdrawal::with('freelancer')->findOrFail($id);
        
        // Get financial data
        $balance = $this->getFreelancerBalance($withdrawal->freelancer->id);
        
        // Get total earnings
        $total_earnings = DB::table('freelancer_earnings')
            ->where('freelancer_id', $withdrawal->freelancer->id)
            ->sum('amount');
            
        // Get previous withdrawals
        $previous_withdrawals = DB::table('withdrawals')
            ->where('user_id', $withdrawal->user_id)
            ->where('id', '!=', $withdrawal->id)
            ->where('status', 'completed')
            ->sum('amount');
        
        // Format payment details as HTML instead of trying to parse JSON
        $paymentDetailsHtml = '';
        
        if (!empty($withdrawal->payment_details)) {
            $details = is_string($withdrawal->payment_details) ? 
                       json_decode($withdrawal->payment_details, true) : 
                       $withdrawal->payment_details;
            
            if ($details) {
                $paymentDetailsHtml = '<ul class="withdrawal-payment-details">';
                
                // Stripe Connect
                if ($withdrawal->payment_method == 'stripe' && isset($details['stripe_account_id'])) {
                    $paymentDetailsHtml .= '<li><strong>Account ID:</strong> ' . $details['stripe_account_id'] . '</li>';
                }
                
                // Bank Transfer
                if ($withdrawal->payment_method == 'bank_transfer') {
                    if (isset($details['bank_name'])) {
                        $paymentDetailsHtml .= '<li><strong>Bank:</strong> ' . $details['bank_name'] . '</li>';
                    }
                    if (isset($details['account_name'])) {
                        $paymentDetailsHtml .= '<li><strong>Account Name:</strong> ' . $details['account_name'] . '</li>';
                    }
                    if (isset($details['account_number'])) {
                        $paymentDetailsHtml .= '<li><strong>Account #:</strong> ' . $details['account_number'] . '</li>';
                    }
                }
                
                // GCash
                if ($withdrawal->payment_method == 'gcash') {
                    if (isset($details['gcash_name'])) {
                        $paymentDetailsHtml .= '<li><strong>Name:</strong> ' . $details['gcash_name'] . '</li>';
                    }
                    if (isset($details['gcash_number'])) {
                        $paymentDetailsHtml .= '<li><strong>Number:</strong> ' . $details['gcash_number'] . '</li>';
                    }
                }
                
                $paymentDetailsHtml .= '</ul>';
            }
        }
        
        // Add the additional data
        $withdrawal->balance = $balance;
        $withdrawal->total_earnings = $total_earnings;
        $withdrawal->previous_withdrawals = $previous_withdrawals;
        $withdrawal->payment_details_html = $paymentDetailsHtml;
        
        // Remove the original payment_details to avoid JSON parsing issues
        unset($withdrawal->payment_details);
        
        return response()->json([
            'success' => true,
            'withdrawal' => $withdrawal
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error fetching withdrawal details', [
            'withdrawal_id' => $id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Error loading withdrawal details: ' . $e->getMessage()
        ], 500);
    }
}
}