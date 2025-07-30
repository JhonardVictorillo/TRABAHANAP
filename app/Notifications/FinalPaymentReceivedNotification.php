<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FinalPaymentReceivedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
   public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
     public function via(object $notifiable): array
    {
        // Send via both mail and database for maximum visibility
        return ['mail', 'database'];
    }
    /**
     * Get the mail representation of the notification.
     */
   public function toMail(object $notifiable): MailMessage
    {
        $amount = number_format($this->appointment->total_amount, 2);
        $date = $this->appointment->date;
        $time = $this->appointment->time;
        
        // Get customer name (assuming there's a relationship to a User model)
        $customerName = 'Customer';
        if ($this->appointment->customer) {
            $customerName = $this->appointment->customer->name;
        }

        return (new MailMessage)
            ->subject('Payment Received for Your Service')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Good news! You have received a payment of ₱' . $amount . ' for your completed service.')
            ->line('Appointment details:')
            ->line('• Customer: ' . $customerName)
            ->line('• Date: ' . $date)
            ->line('• Time: ' . $time)
            ->line('• Amount: ₱' . $amount)
            ->action('View Appointment Details', url('/freelancer/appointments'))
            ->line('The payment has been processed successfully and will be included in your next payout.')
            ->line('Thank you for using MinglaGawa!');
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {   
         // Get customer name - using firstname + lastname if available
        $customerName = 'Customer';
        if ($this->appointment->customer) {
            if (isset($this->appointment->customer->firstname) && isset($this->appointment->customer->lastname)) {
                $customerName = $this->appointment->customer->firstname . ' ' . $this->appointment->customer->lastname;
            } elseif (isset($this->appointment->customer->name)) {
                $customerName = $this->appointment->customer->name;
            }
        }

        return [
              'appointment_id' => $this->appointment->id,
            'customer_id' => $this->appointment->customer_id,
            'customer_name' => $customerName,
            'amount' => $this->appointment->total_amount,
            'date' => $this->appointment->date,
            'time' => $this->appointment->time,
            'message' => "{$customerName} has paid ₱" . number_format($this->appointment->total_amount, 2) . " for your completed service",
            'type' => 'payment_received'
        ];
    }
}
