<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the ticket.
     */
    public function update(User $user, Ticket $ticket): Response
    {
        if ($user->role === 'admin') {
            return Response::allow();
        }

        if ($user->role === 'support') {
            return Response::allow();
        }

        return $user->id === $ticket->user_id 
            ? Response::allow()
            : Response::deny('You do not have permission to update this ticket.');
    }

    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->role === 'admin') {
            return true;
        }
        
        return null;
    }
}
