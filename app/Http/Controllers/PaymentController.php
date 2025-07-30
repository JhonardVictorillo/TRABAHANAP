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
    private function setupStripe()
{
    Stripe::setApiKey(config('services.stripe.secret'));
}
    
    public function createCheckoutSession(Request $request)
    {
       $this->setupStripe();

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'php',
                    'product_data' => [
                        'name' => 'Commitment Fee',
                    ],
                  'unit_amount' => $request->commitment_fee * 100, // amount in cents
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
                'commitment_fee' => $request->commitment_fee,
            ],
            ]);

        return redirect($session->url);
    }

    public function success(Request $request)
{
    $this->setupStripe();
    $session = \Stripe\Checkout\Session::retrieve($request->get('session_id'));
    $meta = $session->metadata;

    $customer = \App\Models\User::find($meta->customer_id);

    $existing = \App\Models\Appointment::where([
        ['freelancer_id', $meta->freelancer_id],
        ['customer_id', $meta->customer_id],
        ['date', $meta->date],
        ['time', $meta->time],
    ])->first();

    if (!$existing) {
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
            'commitment_fee' => $meta->commitment_fee,
            'fee_status' => 'paid',
            'status' => 'pending',
            'stripe_session_id' => $session->id,
        ]);
        // Notify the freelancer
        $freelancer = \App\Models\User::find($meta->freelancer_id);
        if ($freelancer) {
            $freelancer->notify(new \App\Notifications\AppointmentRequest($appointment));
        }
    }
    return view('payment.success');
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
        
        // Calculate platform fee (10%)
        $platformFee = $finalAmount * 0.10;
        $freelancerAmount = $finalAmount - $platformFee;
        
        // Store amount in appointment
        $appointment->total_amount = $finalAmount;
        $appointment->save();
        
        // Use the same approach as commitment fee (which works)
        // IMPORTANT: Generate URLs consistently with your commitment fee code
      $successUrl = url('/payment/final-success') . '?session_id={CHECKOUT_SESSION_ID}&success=true';
        $cancelUrl = url('/payment/final-cancel') . '?appointment_id=' . $appointment->id;
        
        \Log::info('Stripe redirect URLs', [
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl
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
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        
        // Get session ID from request
        $sessionId = $request->get('session_id');
        
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
        
        // Check if appointment exists
        if (!$appointment) {
            \Log::error('No appointment found with session ID', ['session_id' => $sessionId]);
            return redirect()->route('customer.appointments.view')
                ->with('error', 'Invalid payment session');
        }
        
        // Begin database transaction
        DB::beginTransaction();
        
        try {
            // Update appointment payment status
            $appointment->final_payment_status = 'paid';
            $appointment->save();
            
            // Calculate final payment amount (total minus commitment fee)
            $finalPaymentAmount = $appointment->total_amount - $appointment->commitment_fee;
            
            // Record platform revenue (10% commission)
            \App\Models\PlatformRevenue::create([
                'amount' => $finalPaymentAmount * 0.1, // 10% platform fee
                'source' => 'final_payment_commission',
                'appointment_id' => $appointment->id,
                'user_id' => $appointment->freelancer_id,
                'date' => now()->format('Y-m-d'),
                'notes' => 'Platform commission from final payment',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Record freelancer earning (90% of final payment) - THIS IS THE KEY CHANGE
            \App\Models\FreelancerEarning::create([
                'freelancer_id' => $appointment->freelancer_id,
                'appointment_id' => $appointment->id,
                'amount' => $finalPaymentAmount * 0.9, // 90% to freelancer
                'source' => 'service_payment',
                'date' => now()->format('Y-m-d'),
                'notes' => "Final payment for appointment #{$appointment->id}",
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Commit the transaction
            DB::commit();
            
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
                
                // Record platform revenue (10% commission)
                PlatformRevenue::create([
                    'user_id' => $appointmentCommitment->freelancer_id,
                    'appointment_id' => $appointmentCommitment->id,
                    'amount' => $appointmentCommitment->commitment_fee * 0.1, // 10% platform fee
                    'source' => 'commitment_fee_commission',
                    'date' => Carbon::now()->format('Y-m-d'),
                    'notes' => 'Platform commission from commitment fee'
                ]);
                
                // Record freelancer earning (90% of commitment fee)
                FreelancerEarning::create([
                    'freelancer_id' => $appointmentCommitment->freelancer_id,
                    'appointment_id' => $appointmentCommitment->id,
                    'amount' => $appointmentCommitment->commitment_fee * 0.9, // 90% to freelancer
                    'source' => 'commitment_fee',
                    'date' => Carbon::now()->format('Y-m-d'),
                    'notes' => "Commitment fee for appointment #{$appointmentCommitment->id}"
                ]);
                
                // Additional processing like notifications...
            }
            
            // Check if this is a final payment
            $appointmentFinal = Appointment::where('final_stripe_session_id', $sessionId)->first();
            if ($appointmentFinal) {
                // Update appointment status
                $appointmentFinal->final_payment_status = 'paid';
                $appointmentFinal->save();
                
                // Calculate final payment amount (total minus commitment fee)
                $finalPaymentAmount = $appointmentFinal->total_amount - $appointmentFinal->commitment_fee;
                
                // Record platform revenue (10% commission)
                PlatformRevenue::create([
                    'user_id' => $appointmentFinal->freelancer_id,
                    'appointment_id' => $appointmentFinal->id,
                    'amount' => $finalPaymentAmount * 0.1, // 10% platform fee
                    'source' => 'final_payment_commission',
                    'date' => Carbon::now()->format('Y-m-d'),
                    'notes' => 'Platform commission from final payment'
                ]);
                
                // Record freelancer earning (90% of final payment)
                FreelancerEarning::create([
                    'freelancer_id' => $appointmentFinal->freelancer_id,
                    'appointment_id' => $appointmentFinal->id,
                    'amount' => $finalPaymentAmount * 0.9, // 90% to freelancer
                    'source' => 'service_payment',
                    'date' => Carbon::now()->format('Y-m-d'),
                    'notes' => "Final payment for appointment #{$appointmentFinal->id}"
                ]);
                
                // Additional processing like notifications...
            }
        }
        
        // Other webhook event handling...
        
        return response()->json(['status' => 'success']);
    }
    

}