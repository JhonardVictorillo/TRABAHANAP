<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentCanceled extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $appointment;

    public function __construct($appointment)
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
        ->subject('Appointment Canceled')
        ->greeting('Hello ' . $notifiable->firstname . ',')
        ->line('The appointment scheduled on ' . $this->appointment->date . ' at ' . $this->appointment->time . ' has been canceled by the customer.')
        ->line('If you have any questions, please contact support.')
        ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable)
{
    return [
        'message' => 'The appointment scheduled on ' . $this->appointment->date . ' at ' . $this->appointment->time . ' has been canceled by the customer.',
        'appointment_id' => $this->appointment->id,
        'date' => $this->appointment->date,
        'time' => $this->appointment->time,
    ];
}

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
