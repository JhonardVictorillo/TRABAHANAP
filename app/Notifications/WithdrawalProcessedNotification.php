<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawalProcessedNotification extends Notification
{
    use Queueable;

    protected $withdrawal;

    /**
     * Create a new notification instance.
     */
    public function __construct(Withdrawal $withdrawal)
    {
        $this->withdrawal = $withdrawal;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $amount = number_format($this->withdrawal->amount, 2);
        
        return (new MailMessage)
            ->subject('Your Withdrawal Request Has Been Processed')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Good news! Your withdrawal request for ₱' . $amount . ' has been processed.')
            ->line('You should receive the funds in your account within 1-3 business days, depending on your payment method.')
            ->line('Payment Method: ' . ucfirst($this->withdrawal->payment_method))
            ->line('Reference Number: ' . $this->withdrawal->id)
            ->action('View Details', url('/freelancer/withdrawals/' . $this->withdrawal->id))
            ->line('Thank you for using MinglaGawa!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'withdrawal_id' => $this->withdrawal->id,
            'amount' => $this->withdrawal->amount,
            'payment_method' => $this->withdrawal->payment_method,
            'message' => 'Your withdrawal request for ₱' . number_format($this->withdrawal->amount, 2) . ' has been processed.',
            'type' => 'withdrawal_processed'
        ];
    }
}
