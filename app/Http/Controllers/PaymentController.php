<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use App\Models\Post;
use Stripe\Checkout\Session;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Notifications\FinalPaymentNotification;
use App\Models\PlatformRevenue;
use App\Models\FreelancerEarning;
use Carbon\Carbon;

class PaymentController extends Controller
{   
      private $commitmentFee = 30; // Fixed ₱30 (minimum for Stripe)
    private $platformCommissionRate = 0.05; // 5% platform fee
    
    private function setupStripe()
{
    Stripe::setApiKey(config('services.stripe.secret'));
}
    
    public function createCheckoutSession(Request $request)
    {   
       $this->setupStripe();

        $commitmentFee = $this->commitmentFee;

        $user = auth()->user();
    
    // Check if user can book appointments based on violations
    if ($user->is_suspended || $user->is_banned) {
        $message = $user->is_suspended 
            ? 'Your account is currently suspended until ' . $user->suspension_end->format('M d, Y') . '.' 
            : 'Your account has been banned due to policy violations.';
            
        return redirect()->back()->with('error', 'Unable to book appointment. ' . $message . ' Please contact support for assistance.');
    }
    
    // If user has restrictions but isn't fully suspended
    if ($user->is_restricted && (!$user->restriction_end || now()->lessThan($user->restriction_end))) {
        // Check if they already have pending bookings
        $pendingBookings = Appointment::where('customer_id', $user->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->count();
            
        if ($pendingBookings > 0) {
            return redirect()->back()->with('error', 'Due to previous violations, you can only have one pending appointment at a time.');
        }
    }
        
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'php',
                    'product_data' => [
                        'name' => 'Commitment Fee',
                    ],
                  'unit_amount' => $commitmentFee * 100, // amount in cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
           'success_url' => route('payment.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
          'cancel_url' => route('payment.cancel', ['post_id' => $request->post_id]),
            'metadata' => [
                'freelancer_id' => $request->freelancer_id,
                'customer_id' => auth()->id(),
                'post_id' => $request->post_id,
                'date' => $request->date,
                'time' => $request->time,
                'notes' => $request->notes,
                'commitment_fee' => $commitmentFee,
            ],
            ]);

        return redirect($session->url);
    }

    public function success(Request $request)
{
    $this->setupStripe();
    
    try {
        $session = \Stripe\Checkout\Session::retrieve($request->get('session_id'));
        $meta = $session->metadata;
        
        $customer = \App\Models\User::find($meta->customer_id);
        $commitmentFee = $meta->commitment_fee;
        
        \Log::info('Processing commitment fee payment', [
            'session_id' => $session->id,
            'commitment_fee' => $commitmentFee,
            'customer_id' => $meta->customer_id
        ]);
        
        $existing = \App\Models\Appointment::where([
            ['freelancer_id', $meta->freelancer_id],
            ['customer_id', $meta->customer_id],
            ['date', $meta->date],
            ['time', $meta->time],
        ])->first();
        
        if (!$existing) {
            // Use transaction for data consistency
            DB::beginTransaction();
            
            try {
                $appointment = \App\Models\Appointment::create([
                    'freelancer_id' => $meta->freelancer_id,
                    'customer_id' => $meta->customer_id,
                    'post_id' => $meta->post_id,
                    'date' => $meta->date,
                    'time' => $meta->time,
                    'name' => $customer ? ($customer->firstname . ' ' . $customer->lastname) : 'N/A',
                    'address' => $customer ? ($customer->province . ' ' . $customer->city) : 'N/A',
                    'contact' => $customer ? ($customer->contact_number ?? 'N/A') : 'N/A',
                    'notes' => $meta->notes,
                    'commitment_fee' => $commitmentFee,
                    'fee_status' => 'paid',
                    'status' => 'pending',
                    'stripe_session_id' => $session->id,
                ]);
                
                // Create a "held" record for the commitment fee (in escrow)
                PlatformRevenue::create([
                    'amount' => $commitmentFee,
                    'source' => 'commitment_fee_held',
                    'appointment_id' => $appointment->id,
                    'user_id' => $meta->freelancer_id,
                    'date' => now()->format('Y-m-d'),
                    'notes' => "Commitment fee held pending service completion",
                    'status' => 'held', // Mark as held, not collected
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                // Notify the freelancer
                $freelancer = \App\Models\User::find($meta->freelancer_id);
                if ($freelancer) {
                    $freelancer->notify(new \App\Notifications\AppointmentRequest($appointment));
                }
                
                DB::commit();
                
                \Log::info('Commitment fee processing completed', [
                    'appointment_id' => $appointment->id
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Error processing commitment fee', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return redirect()->route('customer.dashboard')
                    ->with('error', 'Error processing payment: ' . $e->getMessage());
            }
        }
        
        return view('payment.success');
    } catch (\Exception $e) {
        \Log::error('Error retrieving Stripe session', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->route('customer.dashboard')
            ->with('error', 'Error processing payment: ' . $e->getMessage());
    }
}

public function cancel(Request $request)
    {
        $postId = $request->input('post_id'); // or session('post_id')
    $post = Post::with('freelancer')->find($postId);
        return view('payment.cancel', compact('post'));
    }



  public function createFinalPaymentSession(Request $request, $id)
{
    try {
        // Set Stripe API key
        $this->setupStripe();
        
        // Find appointment with fully qualified namespace
        $appointment = Appointment::findOrFail($id);
        
        // Authorization check
        if ($appointment->customer_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }
        
        // Validate the request
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);
        
        $finalAmount = $request->amount;
        
      if ($appointment->customer_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }
        
        // Validate the request
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);
        
        $finalAmount = $request->amount;
        
        // Calculate platform fee using reduced rate
        $platformFee = $finalAmount * $this->platformCommissionRate;
        $freelancerAmount = $finalAmount - $platformFee;
        
        // Store amount in appointment
        $appointment->total_amount = $finalAmount;
        $appointment->save();
        
        // Include appointment ID in success URL
        $successUrl = url('/payment/final-success') . '?session_id={CHECKOUT_SESSION_ID}&appointment_id=' . $appointment->id;
        $cancelUrl = url('/payment/final-cancel') . '?appointment_id=' . $appointment->id;
        
        \Log::info('Creating final payment Stripe session', [
            'appointment_id' => $appointment->id,
            'amount' => $finalAmount,
            'success_url' => $successUrl
        ]);
        
        // Create Stripe Checkout Session
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'php',
                    'product_data' => [
                        'name' => 'Final Payment for Completed Service',
                        'description' => 'Service on ' . $appointment->date . ' at ' . $appointment->time,
                    ],
                    'unit_amount' => round($finalAmount * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'metadata' => [
                'appointment_id' => $appointment->id,
                'platform_fee' => $platformFee,
                'freelancer_amount' => $freelancerAmount
            ],
        ]);
        
        // Update appointment with session ID
       $appointment->final_stripe_session_id = $session->id; 
        $appointment->save();
        
        // Redirect to Stripe
        return redirect($session->url);
    } catch (\Exception $e) {
        \Log::error('Payment processing error', [
            'appointment_id' => $id,
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->back()->with('error', 'Payment processing error: ' . $e->getMessage());
    }
}
  
  public function finalPaymentSuccess(Request $request)
{
    try {
        // Set Stripe API key
       $this->setupStripe();
        
        // Get session ID from request
        $sessionId = $request->get('session_id');
         $appointmentId = $request->get('appointment_id');
        
        \Log::info('Final payment success callback', [
            'session_id' => $sessionId,
            'appointment_id' => $appointmentId
        ]);
        
        // Handle missing session ID
        if (!$sessionId) {
            \Log::error('Missing session ID in success callback');
            return redirect()->route('customer.appointments.view')
                ->with('error', 'Payment verification failed: Missing session ID');
        }
        
        // Retrieve Stripe session
        $session = \Stripe\Checkout\Session::retrieve($sessionId);
        
        // Get the appointment
        $appointment = \App\Models\Appointment::where('final_stripe_session_id', $sessionId)->first();
        
         // Get the appointment (try both methods)
        if ($appointmentId) {
            $appointment = \App\Models\Appointment::find($appointmentId);
        } else {
            $appointment = \App\Models\Appointment::where('final_stripe_session_id', $sessionId)->first();
        }

        // Check if appointment exists
         if (!$appointment) {
            \Log::error('No appointment found', [
                'session_id' => $sessionId,
                'appointment_id' => $appointmentId
            ]);
            
            return redirect()->route('customer.appointments.view')
                ->with('error', 'Invalid payment session');
        }
        
        // Begin database transaction
        DB::beginTransaction();
        
        try {
            // Update appointment payment status
            $appointment->final_payment_status = 'paid';
            $appointment->save();
            
             $serviceAmount = $appointment->total_amount;
            $commitmentFee = $appointment->commitment_fee;
            
            // Calculate platform commission (5% of service amount)
            $platformCommission = $serviceAmount * $this->platformCommissionRate;
            $freelancerEarnings = $serviceAmount - $platformCommission;
            
            \Log::info('Processing final payment', [
                'appointment_id' => $appointment->id,
                'service_amount' => $serviceAmount,
                'commitment_fee' => $commitmentFee,
                'platform_commission' => $platformCommission,
                'freelancer_earnings' => $freelancerEarnings
            ]);
            
            
            // Record platform revenue (10% commission)
            PlatformRevenue::create([
                 'amount' => $platformCommission,
                'source' => 'service_commission',
                'appointment_id' => $appointment->id,
                'user_id' => $appointment->freelancer_id,
                'date' => now()->format('Y-m-d'),
                'notes' => "Platform commission (" . ($this->platformCommissionRate*100) . "%) from service payment of ₱{$serviceAmount}",
                'status' => 'collected',
                'created_at' => now(),
                'updated_at' => now()
            ]);
             // Release commitment fee from escrow
            $releasedAmount = $this->releaseCommitmentFee($appointment->id);
            
            // Record freelancer earning (service payment)
            FreelancerEarning::create([
                'freelancer_id' => $appointment->freelancer_id,
                'appointment_id' => $appointment->id,
                'amount' => $freelancerEarnings,
                'source' => 'service_payment',
                'date' => now()->format('Y-m-d'),
                'notes' => "Payment for completed services: ₱{$serviceAmount} - ₱{$platformCommission} commission = ₱{$freelancerEarnings}",
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Also record the released commitment fee as separate earning (if found)
            if ($releasedAmount > 0) {
                FreelancerEarning::create([
                    'freelancer_id' => $appointment->freelancer_id,
                    'appointment_id' => $appointment->id,
                    'amount' => $releasedAmount,
                    'source' => 'commitment_fee_bonus',
                    'date' => now()->format('Y-m-d'),
                    'notes' => "Released commitment fee: ₱{$releasedAmount}",
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            // Commit the transaction
            DB::commit();
              \Log::info('Final payment processed successfully', [
                'appointment_id' => $appointment->id,
                'service_payment' => $freelancerEarnings,
                'commitment_fee_released' => $releasedAmount,
                'total_freelancer_earnings' => $freelancerEarnings + $releasedAmount
            ]);
            
             // Process automatic transfer if the freelancer has it enabled
            $serviceCompletionService = app(ServiceCompletionService::class);
            $serviceCompletionService->processAutoTransfer($appointment);
            
            // Send notification
            $freelancer = \App\Models\User::find($appointment->freelancer_id);
            if ($freelancer) {
                $freelancer->notify(new \App\Notifications\FinalPaymentReceivedNotification($appointment));
            }
            
            // Check if view exists before rendering
            if (view()->exists('payment.final-success')) {
                return view('payment.final-success', compact('appointment'));
            } else {
                return redirect()->route('customer.appointments.view')
                    ->with('success', 'Payment processed successfully!');
            }
            
        } catch (\Exception $e) {
            // Rollback on error
            DB::rollBack();
            
            \Log::error('Transaction error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('customer.appointments.view')
                ->with('error', 'Error processing payment: ' . $e->getMessage());
        }
    } catch (\Exception $e) {
        \Log::error('Final payment success handler error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->route('customer.appointments.view')
            ->with('error', 'Payment verification failed: ' . $e->getMessage());
    }
}

public function finalPaymentCancel(Request $request)
{
    // Get appointment ID - identical to commitment fee cancel
    $appointmentId = $request->input('appointment_id');
    
    // Find appointment - maintain consistency
    $appointment = \App\Models\Appointment::findOrFail($appointmentId);
    
    // Show cancel page - same as commitment fee approach
    return view('payment.final-cancel', compact('appointment'));
}

public function stripeWebhook(Request $request)
{
    // Your existing webhook verification code...
    
    // When a checkout.session.completed event is received:
    if ($event->type === 'checkout.session.completed') {
        $session = $event->data->object;
        $sessionId = $session->id;
        
        // Check if this is a commitment fee payment
        $appointmentCommitment = Appointment::where('stripe_session_id', $sessionId)->first();
        if ($appointmentCommitment) {
            // Update appointment status
            $appointmentCommitment->fee_status = 'paid';
            $appointmentCommitment->save();
            
            // Hold the full commitment fee in escrow
            PlatformRevenue::create([
                'user_id' => $appointmentCommitment->freelancer_id,
                'appointment_id' => $appointmentCommitment->id,
                'amount' => $appointmentCommitment->commitment_fee,
                'source' => 'commitment_fee_held',
                'date' => Carbon::now()->format('Y-m-d'),
                'notes' => 'Commitment fee held in escrow pending service completion',
                'status' => 'held'
            ]);
            
            // Additional processing like notifications...
        }
        
        // Check if this is a final payment
        $appointmentFinal = Appointment::where('final_stripe_session_id', $sessionId)->first();
        if ($appointmentFinal) {
            // Update appointment status
            $appointmentFinal->final_payment_status = 'paid';
            $appointmentFinal->save();
            
            // Get the service amount
            $serviceAmount = $appointmentFinal->total_amount;
            
            // Calculate platform commission (5% of service amount)
            $platformCommission = $serviceAmount * $this->platformCommissionRate;
            $freelancerEarnings = $serviceAmount - $platformCommission;
            
            // Record platform revenue
            PlatformRevenue::create([
                'user_id' => $appointmentFinal->freelancer_id,
                'appointment_id' => $appointmentFinal->id,
                'amount' => $platformCommission,
                'source' => 'service_commission',
                'date' => Carbon::now()->format('Y-m-d'),
                'notes' => "Platform commission (" . ($this->platformCommissionRate*100) . "%) from service payment",
                'status' => 'collected'
            ]);
            
            // Release commitment fee from escrow
            $releasedAmount = $this->releaseCommitmentFee($appointmentFinal->id);
            
            // Record freelancer earning (service payment)
            FreelancerEarning::create([
                'freelancer_id' => $appointmentFinal->freelancer_id,
                'appointment_id' => $appointmentFinal->id,
                'amount' => $freelancerEarnings,
                'source' => 'service_payment',
                'date' => Carbon::now()->format('Y-m-d'),
                'notes' => "Payment for completed services"
            ]);
            
            // Also record the released commitment fee as separate earning (if found)
            if ($releasedAmount > 0) {
                FreelancerEarning::create([
                    'freelancer_id' => $appointmentFinal->freelancer_id,
                    'appointment_id' => $appointmentFinal->id,
                    'amount' => $releasedAmount,
                    'source' => 'commitment_fee_bonus',
                    'date' => Carbon::now()->format('Y-m-d'),
                    'notes' => "Released commitment fee"
                ]);
            }
        }
    }
    
    // Other webhook event handling...
    
    return response()->json(['status' => 'success']);
}

    private function releaseCommitmentFee($appointmentId)
{
    // Find held commitment fee record
    $heldRecord = PlatformRevenue::where('appointment_id', $appointmentId)
                    ->where('source', 'commitment_fee_held')
                    ->where('status', 'held')
                    ->first();
                    
    if ($heldRecord) {
        // Mark as released
        $heldRecord->status = 'released';
        $heldRecord->notes .= " - Released on " . now()->format('Y-m-d');
        $heldRecord->save();
        
        \Log::info('Commitment fee released from escrow', [
            'appointment_id' => $appointmentId,
            'amount' => $heldRecord->amount
        ]);
        
        return $heldRecord->amount;
    }
    
    \Log::warning('No held commitment fee found for appointment', [
        'appointment_id' => $appointmentId
    ]);
    
    return 0;
}



}