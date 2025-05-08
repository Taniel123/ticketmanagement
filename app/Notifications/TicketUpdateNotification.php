<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;

class TicketUpdateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;
    protected $updatedBy;
    protected $oldStatus;
    protected $newStatus;

    public function __construct(Ticket $ticket, $updatedBy, $oldStatus, $newStatus)
    {
        $this->ticket = $ticket;
        $this->updatedBy = $updatedBy;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Ticket Status Updated')
            ->greeting('Hello ' . $notifiable->name)
            ->line('Your ticket has been updated.')
            ->line('Ticket Title: ' . $this->ticket->title)
            ->line('Previous Status: ' . ucfirst($this->oldStatus))
            ->line('New Status: ' . ucfirst($this->newStatus))
            ->line('Updated by: ' . $this->updatedBy->name)
            ->action('View Ticket', url('/tickets/' . $this->ticket->id))
            ->line('Thank you for using our application!');
    }
}