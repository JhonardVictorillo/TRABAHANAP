<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Violation;
use App\Models\ViolationAction;

class ViolationNotification extends Notification 
{
   
    protected $violation;
    protected $action;

    /**
     * Create a new notification instance.
     */
    public function __construct(Violation $violation, ViolationAction $action)
    {
        $this->violation = $violation;
        $this->action = $action;
    }

    /**
     * Get the notification's delivery channels.
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
        $message = (new MailMessage)
                    ->subject($this->getEmailSubject())
                    ->greeting('Hello ' . $notifiable->firstname . ',');
                    
        // Add appropriate content based on action type
switch ($this->action->action_type) {
            case 'warning':
                // Different content based on violation type
                if ($this->violation->violation_type == 'late_cancellation') {
                    $message->line('We need to inform you about a policy violation on our platform.')
                        ->line('You have received a warning for cancelling an appointment less than 24 hours before the scheduled time.')
                        ->line('Please ensure you cancel appointments with at least 24 hours notice to avoid fees and penalties.')
                        ->line('Repeated late cancellations may result in temporary suspension of your account.');
                } else {
                    // Default no-show message
                    $message->line('We need to inform you about a policy violation on our platform.')
                        ->line('You have received a warning for missing an appointment without proper cancellation.')
                        ->line('Please ensure you honor your commitments or cancel with adequate notice.')
                        ->line('Repeated violations may result in temporary suspension of your account.');
                }
                break;
                
            case 'suspension':
                $days = $this->action->action_data['days'] ?? 7;
                $suspendedUntil = now()->addDays($days)->format('F j, Y');
                
                // Different content based on violation type
                if ($this->violation->violation_type == 'late_cancellation') {
                    $message->line('Your account has been temporarily suspended due to violation of our terms.')
                        ->line('Reason: Multiple late cancellations (less than 24 hours before appointments).')
                        ->line("Suspension period: {$days} days (until {$suspendedUntil})")
                        ->line('Your account will be automatically reactivated after this period.')
                        ->line('During suspension, you cannot book new appointments or receive booking requests.');
                } else {
                    $message->line('Your account has been temporarily suspended due to violation of our terms.')
                        ->line('Reason: Missing scheduled appointment(s) without proper cancellation.')
                        ->line("Suspension period: {$days} days (until {$suspendedUntil})")
                        ->line('Your account will be automatically reactivated after this period.')
                        ->line('During suspension, you cannot book new appointments or receive booking requests.');
                }
                break;
                
            case 'fee':
                $amount = $this->action->action_data['fee_amount'] ?? 0;
                
                $message->line('A penalty fee has been applied to your account due to a violation.')
                       ->line('Reason: Missing a scheduled appointment without proper cancellation.')
                       ->line("Fee amount: \${$amount}")
                       ->line('This fee will be deducted from your next payment or added to your next bill.')
                       ->line('Please ensure you honor your commitments to avoid future penalties.');
                break;
                
            case 'ban':
                $message->line('Your account has been permanently banned due to repeated violations of our terms.')
                       ->line('Reason: Multiple missed appointments without proper cancellation.')
                       ->line('Your access to our platform has been revoked.')
                       ->line('If you believe this decision was made in error, please contact our support team.');
                break;
                
            case 'restrict':
                $message->line('Restrictions have been applied to your account due to violations of our terms.')
                       ->line('Reason: Missing scheduled appointments without proper cancellation.')
                       ->line('Your booking privileges have been temporarily limited.')
                       ->line('Please ensure you honor your commitments to have full access restored.');
                break;
                
            default:
                $message->line('There has been a violation recorded on your account.')
                       ->line('Please ensure you follow our platform guidelines to avoid penalties.');
        }
        
        // Add notes from admin if available
        if ($this->action->notes) {
            $message->line('Additional notes: ' . $this->action->notes);
        }
        
        return $message->action('Contact Support', url('/contact-us'))
                      ->line('Thank you for using our platform.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
{
    return [
        'violation_id' => $this->violation->id,
        'action_id' => $this->action->id,
        'action_type' => $this->action->action_type,
        'appointment_id' => $this->violation->appointment_id,
        'title' => $this->getNotificationTitle(),
        'message' => $this->getNotificationMessage(),
        // Add this important field that's likely used for recognition in your Header.blade.php
        'type' => 'violation_update'
    ];
}
    
    /**
     * Get the email subject based on action type
     */
    private function getEmailSubject(): string
{
    $actionType = $this->action->action_type;
    $violationType = $this->violation->violation_type;
    
    if ($actionType == 'warning' && $violationType == 'late_cancellation') {
        return 'Warning Notice - Late Appointment Cancellation';
    } else if ($actionType == 'warning') {
        return 'Warning Notice - Missed Appointment';
    } else if ($actionType == 'suspension' && $violationType == 'late_cancellation') {
        return 'Account Suspension Notice - Late Cancellations';
    } else if ($actionType == 'suspension') {
        return 'Account Suspension Notice';
    }
    
    // Default cases
    switch ($actionType) {
        case 'fee':
            return 'Penalty Fee Applied to Your Account';
        case 'ban':
            return 'Account Ban Notice';
        case 'restrict':
            return 'Account Restrictions Applied';
        default:
            return 'Important Notice About Your Account';
    }
}
    
    /**
     * Get the notification title for database notifications
     */
    private function getNotificationTitle(): string
    {
        switch ($this->action->action_type) {
            case 'warning':
                return 'Warning Issued';
            case 'suspension':
                return 'Account Suspended';
            case 'fee':
                return 'Penalty Fee Applied';
            case 'ban':
                return 'Account Banned';
            case 'restrict':
                return 'Account Restricted';
            default:
                return 'Account Violation';
        }
    }
    
    /**
     * Get the notification message for database notifications
     */
    private function getNotificationMessage(): string
{
    $actionType = $this->action->action_type;
    $violationType = $this->violation->violation_type;
     $userRole = $this->violation->user_role ?? null;
    
    if ($actionType == 'warning' && $violationType == 'late_cancellation') {
        return 'You have been issued a warning for cancelling an appointment with less than 24 hours notice.';
    } else if ($actionType == 'suspension' && $violationType == 'late_cancellation') {
        $days = $this->action->action_data['days'] ?? 7;
        return "Your account has been suspended for {$days} days due to multiple late cancellations.";
    }
    
    // Original cases for other violation types
    switch ($actionType) {
        case 'warning':
            return 'You have been issued a warning for missing a scheduled appointment.';
        case 'suspension':
            $days = $this->action->action_data['days'] ?? 7;
            return "Your account has been suspended for {$days} days due to violations.";
        case 'fee':
            $amount = $this->action->action_data['fee_amount'] ?? 0;
            return "A penalty fee of \${$amount} has been applied to your account.";
        case 'ban':
            return 'Your account has been permanently banned due to repeated violations.';
        case 'restrict':
           if ($userRole === 'freelancer') {
                return 'Posting restrictions have been applied to your account. You cannot create new posts until the restriction period ends.';
            } elseif ($userRole === 'customer') {
                return 'Booking restrictions have been applied to your account. You cannot book new appointments until the restriction period ends.';
            } else {
                return 'Restrictions have been applied to your account.';
            }
        default:
            return 'A violation has been recorded on your account.';
    }
}

}

