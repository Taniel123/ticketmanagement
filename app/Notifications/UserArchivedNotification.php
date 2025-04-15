<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class UserArchivedNotification extends Notification
{
    use Queueable;

    protected $archivedUser;

    public function __construct(User $archivedUser)
    {
        $this->archivedUser = $archivedUser;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('User Account Archived')
            ->line('You have archived the following user account:')
            ->line('Name: ' . $this->archivedUser->name)
            ->line('Email: ' . $this->archivedUser->email)
            ->line('Role: ' . ucfirst($this->archivedUser->role))
            ->line('Archive Date: ' . now()->format('Y-m-d H:i:s'));
    }
}