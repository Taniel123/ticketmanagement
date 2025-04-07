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
    protected $oldStatus;

    public function __construct($ticket = null, string $action, $performer, $oldStatus = null)
    {
        $this->ticket = $ticket;
        $this->action = $action;
        $this->performer = $performer;
        $this->oldStatus = $oldStatus;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = new MailMessage;
        
        switch ($this->action) {
            case 'user_approved':
                return $message
                    ->subject('Account Approved')
                    ->line('Your account has been approved.')
                    ->line('You can now access the full features of the system.')
                    ->line('Approved by: ' . $this->performer->name)
                    ->action('Login Now', route('login'));

            case 'user_role_changed':
                return $message
                    ->subject('Role Updated')
                    ->line('Your account role has been updated.')
                    ->line('Your new role is: ' . ucfirst($notifiable->role))
                    ->line('Updated by: ' . $this->performer->name)
                    ->action('Go to Dashboard', route('login'));

            case 'created':
                return $message
                    ->subject('New Ticket Created')
                    ->line('A new ticket has been created.')
                    ->line('Title: ' . $this->ticket->title)
                    ->line('Priority: ' . ucfirst($this->ticket->priority))
                    ->line('Description: ' . $this->ticket->description)
                    ->action('View Ticket', route('tickets.show', $this->ticket));

            case 'status_updated':
                return $message
                    ->subject('Ticket Status Updated')
                    ->line('The status of your ticket has been updated.')
                    ->line('Title: ' . $this->ticket->title)
                    ->line('Previous Status: ' . ucfirst($this->oldStatus))
                    ->line('New Status: ' . ucfirst($this->ticket->status))
                    ->line('Updated by: ' . $this->performer->name)
                    ->action('View Ticket', route('tickets.show', $this->ticket));

            default:
                return $message
                    ->subject('Notification')
                    ->line('You have a new notification.');
        }
    }
}