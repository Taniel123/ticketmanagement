<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    // Create the ticket
    $ticket = Ticket::create([
        'title' => $validatedData['title'],
        'description' => $validatedData['description'],
        'priority' => $validatedData['priority'],
        'status' => 'open',
        'user_id' => Auth::id(),
        'created_by' => Auth::id()
    ]);

    // Redirect with success message and ticket title for popup notification
    return redirect()->route('tickets.create')
        ->with('ticket_created', $ticket->title);  // Pass ticket title to view for the popup
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

        // Send notification if status changed to closed
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
        'status' => 'required|in:open,ongoing,closed',
    ]);

    // Update ticket status
    $ticket->update($validatedData);

    // Flash message with ticket title
    return redirect()->route('support.dashboard') // Adjust to your route
        ->with('ticket_updated', 'The status of ticket ' . $ticket->title . ' has been updated.');
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