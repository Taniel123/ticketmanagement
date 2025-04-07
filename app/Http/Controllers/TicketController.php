<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TicketNotification;

class TicketController extends Controller
{
    // Index - Get all tickets based on user role
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            $tickets = Ticket::with('user')->latest()->get();
        } elseif (Auth::user()->role === 'support') {
            $tickets = Ticket::with('user')
                ->whereIn('status', ['open', 'ongoing'])
                ->latest()
                ->get();
        } else {
            $tickets = Ticket::where('user_id', Auth::id())
                ->latest()
                ->get();
        }

        return view('tickets.index', compact('tickets'));
    }

    // Show create form
    public function create()
    {
        return view('tickets.create');
    }

    // Store new ticket
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        $ticket = Ticket::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'priority' => $validatedData['priority'],
            'status' => 'open',
            'user_id' => Auth::id(),
            'created_by' => Auth::id()
        ]);

        // Notify user
        $ticket->user->notify(new TicketNotification($ticket, 'created', auth()->user()));

        // Notify support team
        $supportUsers = User::where('role', 'support')->get();
        foreach ($supportUsers as $supportUser) {
            $supportUser->notify(new TicketNotification($ticket, 'new_ticket_for_support', auth()->user()));
        }

        return redirect()->route('user.dashboard')
            ->with('success', 'Ticket created successfully.');
    }

    // Show single ticket
    public function show(Ticket $ticket)
    {
        // Check if user can view this ticket
        if (Auth::user()->role === 'user' && Auth::id() !== $ticket->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $ticket->load('user');
        return view('tickets.show', compact('ticket'));
    }

    // Update ticket status
    public function updateStatus(Request $request, Ticket $ticket)
    {
        if (!in_array(auth()->user()->role, ['support', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:open,ongoing,closed'
        ]);

        $oldStatus = $ticket->status;
        $ticket->status = $request->status;
        $ticket->save();

        // Notify ticket owner
        $ticket->user->notify(new TicketNotification(
            $ticket, 
            'status_updated', 
            auth()->user(),
            $oldStatus
        ));

        // If closed, send additional notification
        if ($request->status === 'closed' && $oldStatus !== 'closed') {
            $ticket->user->notify(new TicketNotification($ticket, 'closed', auth()->user()));
        }

        return redirect()->back()->with('success', 'Ticket status updated successfully');
    }

    // Update ticket
    public function update(Request $request, Ticket $ticket)
    {
        // Verify ownership or admin/support role
        if (Auth::user()->role === 'user' && Auth::id() !== $ticket->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        $ticket->update($validatedData);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket updated successfully.');
    }

    // Delete ticket (admin only)
    public function destroy(Ticket $ticket)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket deleted successfully.');
    }
}
