<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;
    protected $type;
    protected $actionUser;
    protected $oldStatus;

    public function __construct(Ticket $ticket, $type, User $actionUser, $oldStatus = null)
    {
        $this->ticket = $ticket;
        $this->type = $type;
        $this->actionUser = $actionUser;
        $this->oldStatus = $oldStatus;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = new MailMessage;

        switch ($this->type) {
            case 'created':
                return $message
                    ->subject('New Ticket Created')
                    ->line('Your ticket has been created successfully.')
                    ->line('Ticket Title: ' . $this->ticket->title)
                    ->line('Priority: ' . ucfirst($this->ticket->priority))
                    ->action('View Ticket', url('/tickets/' . $this->ticket->id))
                    ->line('We will review your ticket shortly.');

            // case 'new_ticket_for_support':
            //     return $message
            //         ->subject('New Support Ticket')
            //         ->line('A new ticket requires your attention.')
            //         ->line('Ticket Title: ' . $this->ticket->title)
            //         ->line('Created by: ' . $this->ticket->user->name)
            //         ->line('Priority: ' . ucfirst($this->ticket->priority))
            //         ->action('View Ticket', url('/tickets/' . $this->ticket->id));

            case 'status_updated':
                return $message
                    ->subject('Ticket Status Updated')
                    ->line('Your ticket status has been updated.')
                    ->line('Ticket Title: ' . $this->ticket->title)
                    ->line('New Status: ' . ucfirst($this->ticket->status))
                    ->line('Updated by: ' . $this->actionUser->name)
                    ->action('View Ticket', url('/tickets/' . $this->ticket->id));

            case 'closed':
                return $message
                    ->subject('Ticket Closed')
                    ->line('Your ticket has been closed.')
                    ->line('Ticket Title: ' . $this->ticket->title)
                    ->line('Closed by: ' . $this->actionUser->name)
                    ->action('View Ticket', url('/tickets/' . $this->ticket->id));

            default:
                return $message
                    ->subject('Ticket Update')
                    ->line('There has been an update to your ticket.')
                    ->action('View Ticket', url('/tickets/' . $this->ticket->id));
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'priority' => 'required|in:low,medium,high',
            ]);

            \DB::beginTransaction();

            $ticket = Ticket::create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'priority' => $validatedData['priority'],
                'status' => 'open',
                'user_id' => Auth::id(),
                'created_by' => Auth::id()
            ]);

            // Notify ticket creator
            $ticket->user->notify(new TicketNotification($ticket, 'created', auth()->user()));

            // Notify support team
            $supportUsers = User::where('role', 'support')->get();
            foreach ($supportUsers as $supportUser) {
                $supportUser->notify(new TicketNotification($ticket, 'new_ticket_for_support', auth()->user()));
            }

            \DB::commit();

            return redirect()->route('user.dashboard')
                ->with('success', 'Ticket created successfully. You will receive an email confirmation.');

        } catch (\Exception $e) {
            \DB::rollback();
            return back()->with('error', 'Failed to create ticket. Please try again.');
        }
    }
}