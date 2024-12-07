<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentStatusUpdated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct($appointment, $status)
    {
        $this->appointment = $appointment;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database']; // You can choose email, database, etc.
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
        ->subject('Appointment Status Updated')
        ->line('Your appointment with ' . $this->appointment->freelancer->firstname . ' ' . $this->appointment->freelancer->lastname . ' has been ' . $this->status . '.')
        ->line('Date: ' . $this->appointment->date . ' | Time: ' . $this->appointment->time)
        ->action('View Appointment', url('/appointments/' . $this->appointment->id))
        ->line('Thank you for using our service!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'status' => $this->status,
            'freelancer_name' => $this->appointment->freelancer->firstname . ' ' . $this->appointment->freelancer->lastname,
            'message' => 'Your appointment with ' . $this->appointment->freelancer->firstname . ' ' . $this->appointment->freelancer->lastname . ' has been ' . $this->status . '.',
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
            'appointment_id' => $this->appointment->id,
            'status' => $this->status,
            'freelancer_name' => $this->appointment->freelancer->firstname . ' ' . $this->appointment->freelancer->lastname,
            'message' => 'Your appointment with ' . $this->appointment->freelancer->firstname . ' ' . $this->appointment->freelancer->lastname . ' has been ' . $this->status . '.',
        ];
    }
}
