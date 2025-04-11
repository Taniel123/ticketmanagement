<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RoleChangeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $newRole;
    protected $actionUser;

    public function __construct($newRole, $actionUser)
    {
        $this->newRole = $newRole;
        $this->actionUser = $actionUser;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Role Updated')
            ->line('Your role has been updated.')
            ->line('New Role: ' . ucfirst($this->newRole))
            ->line('Updated by: ' . $this->actionUser->name)
            ->action('View Dashboard', url('/dashboard'))
            ->line('Thank you for using our application!');
    }
}