<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use App\Models\Post;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
       Stripe::setApiKey(config('services.stripe.secret'));

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
    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
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
}