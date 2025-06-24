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
    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
                    ->subject($this->isApproved ? 'Category Request Approved' : 'Category Request Declined')
                    ->greeting('Hello ' . $notifiable->firstname . '!');
                    
        if ($this->isApproved) {
            $message->line('Good news! Your category request has been approved.')
                    ->line('Requested Category: ' . $this->categoryRequest->category_name)
                    ->line('This category is now available for selection in your profile.');
        } else {
            $message->line('Your category request could not be approved at this time.')
                    ->line('Requested Category: ' . $this->categoryRequest->category_name);
                    
            if ($this->categoryRequest->admin_notes) {
                $message->line('Reason: ' . $this->categoryRequest->admin_notes);
            }
            
            $message->line('You can submit a different category request if needed.');
        }
        
        return $message->action('Update Your Profile', url('/freelancer/profile'))
                      ->line('Thank you for helping us improve our platform!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
     public function toArray(object $notifiable): array
    {
        return [
            'category_request_id' => $this->categoryRequest->id,
            'category_name' => $this->categoryRequest->category_name,
            'status' => $this->isApproved ? 'approved' : 'declined',
            'admin_notes' => $this->categoryRequest->admin_notes,
            'type' => 'category_request_processed'
        ];
    }
}
