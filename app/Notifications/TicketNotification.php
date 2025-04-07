<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;

class TicketNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;
    protected $action;
    protected $performer;

    public function __construct(Ticket $ticket, string $action, $performer)
    {
        $this->ticket = $ticket;
        $this->action = $action;
        $this->performer = $performer;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = new MailMessage;
        
        switch ($this->action) {
            case 'created':
                $message->subject('New Ticket Created')
                       ->line('A new ticket has been created.')
                       ->line('Title: ' . $this->ticket->title)
                       ->line('Priority: ' . ucfirst($this->ticket->priority));
                break;
                
            case 'status_updated':
                $message->subject('Ticket Status Updated')
                       ->line('The status of your ticket has been updated.')
                       ->line('New Status: ' . ucfirst($this->ticket->status))
                       ->line('Updated by: ' . $this->performer->name);
                break;
                
            case 'closed':
                $message->subject('Ticket Closed')
                       ->line('Your ticket has been closed.')
                       ->line('Title: ' . $this->ticket->title)
                       ->line('Closed by: ' . $this->performer->name);
                break;
        }

        return $message->action('View Ticket', route('tickets.show', $this->ticket))
                      ->line('Thank you for using our application!');
    }
}