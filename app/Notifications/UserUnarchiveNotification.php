<?php

// namespace App\Notifications;

// use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Notifications\Messages\MailMessage;
// use Illuminate\Notifications\Notification;
// use App\Models\User;

// class UserUnarchiveNotification extends Notification
// {
//     use Queueable;

//     protected $unarchivedUser;

//     public function __construct(User $unarchivedUser)
//     {
//         $this->unarchivedUser = $unarchivedUser;
//     }

//     public function via($notifiable)
//     {
//         return ['mail'];
//     }

//     public function toMail($notifiable)
//     {
//         return (new MailMessage)
//             ->subject('User Account Unarchived')
//             ->line('You have unarchived the following user account:')
//             ->line('Name: ' . $this->unarchivedUser->name)
//             ->line('Email: ' . $this->unarchivedUser->email)
//             ->line('Role: ' . ucfirst($this->unarchivedUser->role))
//             ->line('Unarchive Date: ' . now()->format('Y-m-d H:i:s'));
//     }
// }
