<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function markNoShow(Request $request, $id)
    {
        $appointment = \App\Models\Appointment::findOrFail($id);
    
        // Only allow after scheduled time
        if (now()->lt($appointment->date . ' ' . $appointment->time)) {
            return back()->with('error', 'You can only mark no-show after the scheduled time.');
        }
    
        if ($request->user()->id === $appointment->customer_id) {
            // Customer marks freelancer as no-show
            $appointment->status = 'no_show_freelancer';
            $appointment->save();

            $freelancer = $appointment->freelancer;
            $freelancer->increment('no_show_count');
            $freelancer->increment('violation_count');
            $freelancer->last_violation_at = now();
            $freelancer->save();
    
            // Refund the customer if fee was paid
            if ($appointment->fee_status === 'paid' && $appointment->stripe_session_id) {
                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                $session = \Stripe\Checkout\Session::retrieve($appointment->stripe_session_id);
                $paymentIntentId = $session->payment_intent;
                \Stripe\Refund::create(['payment_intent' => $paymentIntentId]);
                $appointment->fee_status = 'refunded';
                $appointment->save();
            }
        } elseif ($request->user()->id === $appointment->freelancer_id) {
            // Freelancer marks customer as no-show
            $appointment->status = 'no_show_customer';
            $appointment->save();

              // Increment customer's no_show_count and update last_violation_at
                $customer = $appointment->customer;
                $customer->increment('no_show_count');
                $customer->increment('violation_count');
                $customer->last_violation_at = now();
                $customer->save();
            // No refund, freelancer keeps the fee
        }
    
        return back()->with('success', 'No-show status updated.');
    }
}
