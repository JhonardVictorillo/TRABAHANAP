<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\CategoryRequest;

class NewCategoryRequestNotification extends Notification
{
    use Queueable;


    protected $user;
    protected $categoryRequest;

    /**
     * Create a new notification instance.
     */
     public function __construct(User $user, $categoryRequest)
    {
        $this->user = $user;
        $this->categoryRequest = $categoryRequest;
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
        return (new MailMessage)
                    ->subject('New Category Request')
                    ->greeting('Hello Admin!')
                    ->line('A new category has been requested by a freelancer.')
                    ->line('User: ' . $this->user->firstname . ' ' . $this->user->lastname)
                    ->line('Requested Category: ' . $this->categoryRequest)
                    ->action('Review Request', url('/admin/category-requests'))
                    ->line('Thank you for managing the platform!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
             // Create a proper message field that will be displayed in the notification
        $message = $this->user->firstname . ' ' . $this->user->lastname . ' has requested a new category: ' . $this->categoryRequest;
        
        // Add additional information that can be shown when expanded
        $additionalInfo = 'Requested on: ' . now()->format('F j, Y g:i A') . 
                         '\nUser ID: ' . $this->user->id;

        return [
              'message' => $message, // This is the key field that will be displayed
            'additional_info' => $additionalInfo,
            'user_id' => $this->user->id,
            'user_name' => $this->user->firstname . ' ' . $this->user->lastname,
            'category_request' => $this->categoryRequest,
            'type' => 'category_request'
        ];
    }
}
