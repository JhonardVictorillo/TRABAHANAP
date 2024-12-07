<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentRequest extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
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
        ->line('You have a new appointment request from ' . $this->appointment->name)
        ->line('Date: ' . $this->appointment->date . ' | Time: ' . $this->appointment->time)
        ->line('Customer Address: ' . $this->appointment->address) // Add this line
        ->action('View Appointment', url('/appointments/' . $this->appointment->id))
        ->line('Please accept or decline the request.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
           'appointment_id' => $this->appointment->id,
            'customer_name' => $this->appointment->name,
            'date' => $this->appointment->date,
            'time' => $this->appointment->time,
            'address'=>$this->appointment->address
        ];
    }
}
