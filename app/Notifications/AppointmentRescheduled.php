<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;

class AppointmentRescheduled extends Notification
{
    use Queueable;

     protected $appointment;
    protected $oldDate;
    protected $oldTime;

    
    
    /**
     * Create a new notification instance.
     */
     public function __construct(Appointment $appointment, $oldDate, $oldTime)
    {
        $this->appointment = $appointment;
        $this->oldDate = $oldDate;
        $this->oldTime = $oldTime;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Appointment Rescheduled')
            ->greeting('Hello ' . $notifiable->firstname . '!')
            ->line('An appointment has been rescheduled by the customer.')
            ->line('Customer: ' . $this->appointment->name)
            ->line('Service: ' . ($this->appointment->post ? $this->appointment->post->title : 'N/A'))
            ->line('From: ' . $this->oldDate . ' at ' . $this->oldTime)
            ->line('To: ' . $this->appointment->date . ' at ' . $this->appointment->time)
            ->action('View Appointment', url('/freelancer/appointments'))
            ->line('Thank you for using our application!');
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
            'message' => 'Your appointment with ' . $this->appointment->name . ' has been rescheduled from ' . 
                          $this->oldDate . ' at ' . $this->oldTime . ' to ' . 
                          $this->appointment->date . ' at ' . $this->appointment->time,
            'type' => 'reschedule',
        ];
    }
}
