<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TicketUpdateNotification extends Notification
{
    use Queueable;

    protected $ticket;
    protected $oldStatus;
    protected $newStatus;
    protected $feedback;

    public function __construct(Ticket $ticket, string $oldStatus, string $newStatus, string $feedback)
    {
        $this->ticket = $ticket;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->feedback = $feedback;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Ticket #{$this->ticket->id} Updated")
            ->line("Your ticket '{$this->ticket->title}' has been updated.")
            ->line("Status changed from {$this->oldStatus} to {$this->newStatus}.")
            ->line("Feedback: {$this->feedback}")
            ->action('View Ticket', url("/tickets/{$this->ticket->id}"))
            ->line('Thank you for using our application!');
    }
}