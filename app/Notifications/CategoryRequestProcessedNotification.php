<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\CategoryRequest;

class CategoryRequestProcessedNotification extends Notification
{
    use Queueable;

    protected $categoryRequest;
    protected $isApproved;

    /**
     * Create a new notification instance.
     */
    public function __construct(CategoryRequest $categoryRequest, bool $isApproved)
    {
        $this->categoryRequest = $categoryRequest;
        $this->isApproved = $isApproved;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
   public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        // Get the freelancer amount
        $platformRevenue = \DB::table('platform_revenues')
            ->where('appointment_id', $this->appointment->id)
            ->where('source', 'final_payment_freelancer')
            ->first();
            
        $amount = $platformRevenue ? $platformRevenue->amount : $this->appointment->total_amount;
        
        return (new MailMessage)
            ->subject('Payment Received')
            ->line('Good news! You have received payment for your completed service.')
            ->line('Appointment details:')
            ->line('Date: ' . $this->appointment->date)
            ->line('Time: ' . $this->appointment->time)
            ->line('Client: ' . $this->appointment->customer->firstname . ' ' . $this->appointment->customer->lastname)
            ->line('Amount: ₱' . number_format($amount, 2))
            ->action('View Details', url('/freelancer/appointments'))
            ->line('Thank you for using our platform!');
    }
    
    public function toDatabase($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'title' => 'Payment Received',
            'message' => 'You have received payment for your completed service with ' . 
                         $this->appointment->customer->firstname . ' ' . $this->appointment->customer->lastname,
            'type' => 'payment',
        ];
    }
}
