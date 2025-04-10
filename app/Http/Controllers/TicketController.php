<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TicketNotification;
use App\Models\User;
use App\Notifications\TicketUpdateNotification;


class TicketController extends Controller
{
    // Index - Get all tickets based on user role
    public function index()
    {
        $user = Auth::user();
        
        switch ($user->role) {
            case 'admin':
                // Admin can see all tickets
                $tickets = Ticket::with('user')
                    ->latest()
                    ->get();
                break;
                
            case 'support':
                // Support can see open and ongoing tickets
                $tickets = Ticket::with('user')
                    ->whereIn('status', ['open', 'ongoing'])
                    ->latest()
                    ->get();
                break;
                
            default:
                // Regular users can only see their own tickets
                $tickets = Ticket::where('user_id', Auth::id())
                    ->latest()
                    ->get();
                break;
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

    // Show individual ticket
    public function show(Ticket $ticket)
    {
        // Check if user has permission to view this ticket
        if (Auth::user()->role === 'user' && $ticket->user_id !== Auth::id()) {
            return redirect()->route('tickets.index')
                ->with('error', 'You are not authorized to view this ticket.');
        }

        return view('tickets.show', compact('ticket'));
    }

    // Update ticket status (admin and support only)
    public function updateStatus(Request $request, Ticket $ticket)
    {
        try {
            // Validate request
            $request->validate([
                'status' => 'required|in:open,ongoing,closed'
            ]);

            // Store old status
            $oldStatus = $ticket->status;

            // Update ticket status
            $ticket->update([
                'status' => $request->status
            ]);

            // Notify the ticket owner
            $ticket->user->notify(new TicketUpdateNotification(
                $ticket,
                auth()->user(),
                $oldStatus,
                $request->status
            ));

            return back()->with('success', 'Ticket status updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update ticket status: ' . $e->getMessage());
        }
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
