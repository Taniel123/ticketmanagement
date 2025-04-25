<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true; // Everyone can view tickets list
    }

    public function view(User $user, Ticket $ticket)
    {
        return $user->role === 'admin' || 
               $user->role === 'support' || 
               $user->id === $ticket->user_id;
    }

    public function create(User $user)
    {
        return true; // All authenticated users can create tickets
    }

    public function update(User $user, Ticket $ticket)
    {
        if (in_array($user->role, ['admin', 'support'])) {
            return true;
        }

        return $user->id === $ticket->user_id;
    }

    public function delete(User $user, Ticket $ticket)
    {
        return $user->role === 'admin';
    }

    public function addFeedback(User $user, Ticket $ticket)
    {
        return in_array($user->role, ['admin', 'support']) || 
               $user->id === $ticket->user_id;
    }
}