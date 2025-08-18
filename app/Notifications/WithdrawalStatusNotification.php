<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Withdrawal;

class WithdrawalStatusNotification extends Notification
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
    public function via(object $notifiable): array
    {
         return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $status = $this->withdrawal->status;
        $amount = number_format($this->withdrawal->amount, 2);
        
        // Build the subject based on status
        $subject = match ($status) {
            'pending' => 'New Withdrawal Request Submitted',
            'processing' => 'Your Withdrawal Request is Being Processed',
            'completed' => 'Your Withdrawal Has Been Completed',
            'rejected' => 'Your Withdrawal Request Has Been Rejected',
            default => 'Update on Your Withdrawal Request'
        };
        
        $message = (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiable->firstname . '!');
            
        // Build the message content based on status
        switch ($status) {
            case 'pending':
                $message->line('We have received your withdrawal request for ₱' . $amount . '.')
                       ->line('Your request is now pending admin approval.')
                       ->line('We\'ll notify you when your withdrawal request is processed.');
                break;
                
            case 'processing':
                $message->line('Your withdrawal request for ₱' . $amount . ' is now being processed.')
                       ->line('We\'re working on sending your payment through ' . ucfirst($this->withdrawal->payment_method) . '.');
                break;
                
            case 'completed':
                $message->line('Great news! Your withdrawal for ₱' . $amount . ' has been completed.')
                       ->line('You should receive the funds in your account shortly.');
                       
               if ($this->withdrawal->payment_method == 'stripe' && !empty($this->withdrawal->stripe_transfer_id)) {
                     $message->line('Transaction ID: ' . $this->withdrawal->stripe_transfer_id);
    }
                break;
                
            case 'rejected':
                $message->line('Unfortunately, your withdrawal request for ₱' . $amount . ' has been rejected.')
                       ->line('Reason: ' . str_replace("Rejected: ", "", $this->withdrawal->admin_notes ?? 'No reason provided'))
                       ->line('The funds have been returned to your available balance.');
                break;
                
            default:
                $message->line('The status of your withdrawal request for ₱' . $amount . ' has been updated to ' . ucfirst($status) . '.');
        }
        
        return $message->action('View Details', url('/freelancer/withdrawals/' . $this->withdrawal->id))
                      ->line('Thank you for using our platform!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'withdrawal_id' => $this->withdrawal->id,
            'amount' => $this->withdrawal->amount,
            'payment_method' => $this->withdrawal->payment_method,
            'status' => $this->withdrawal->status,
            'message' => $this->getStatusMessage(),
            'type' => 'withdrawal_update'
        ];
    }
    
    /**
     * Get a friendly status message based on status
     */
    private function getStatusMessage(): string
    {
        $amount = number_format($this->withdrawal->amount, 2);
        
        return match ($this->withdrawal->status) {
            'pending' => "Your withdrawal request for ₱{$amount} has been submitted and is pending review.",
            'processing' => "Your withdrawal request for ₱{$amount} is now being processed.",
            'completed' => "Your withdrawal request for ₱{$amount} has been completed!",
            'rejected' => "Your withdrawal request for ₱{$amount} was rejected. Funds have been returned to your balance.",
            default => "Your withdrawal request for ₱{$amount} status has been updated to " . $this->withdrawal->status . "."
        };
    }
}
