<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
                    ->paginate(3); // Adjust per page count
                break;
                
            case 'support':
                // Support can see open and ongoing tickets
                $tickets = Ticket::with('user')
                    ->whereIn('status', ['open', 'ongoing'])
                    ->latest()
                    ->paginate(3);
                break;
                
            default:
                // Regular users can only see their own tickets
                $tickets = Ticket::where('user_id', Auth::id())
                    ->latest()
                    ->paginate(3);
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

        // Redirect with success message and ticket title for popup notification
        return redirect()->route('tickets.create')
        ->with('ticket_created', $ticket->title);  // Pass ticket title to view for the popup
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
        $request->validate([
            'status' => 'required|in:open,ongoing,closed',
            'feedback' => 'required_if:status,ongoing,closed'
        ]);

        try {
            DB::beginTransaction();
            
            // Store the old status before updating
            $oldStatus = $ticket->status;
            
            // Update ticket status
            $ticket->update(['status' => $request->status]);

            // Create feedback if provided
            if ($request->filled('feedback')) {
                // Changed to ticket_feedbacks (plural)
                DB::table('ticket_feedbacks')->insert([
                    'ticket_id' => $ticket->id,
                    'user_id' => auth()->id(),
                    'comment' => $request->feedback,
                    'status_change' => $request->status,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Ticket status updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update ticket status: ' . $e->getMessage());
        }
    }

    // Edit ticket
    public function edit(Ticket $ticket)
    {
        // Allow both admin and support to edit tickets
        if (!in_array(auth()->user()->role, ['admin', 'support'])) {
            abort(403);
        }
        
        return view('tickets.edit', compact('ticket'));
    }

    // Update ticket
    public function update(Request $request, Ticket $ticket)
    {
        // Allow both admin and support to update tickets
        if (!in_array(auth()->user()->role, ['admin', 'support'])) {
            abort(403);
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:open,ongoing,closed',
            'feedback' => 'required_if:status,ongoing,closed'
        ]);

        try {
            DB::beginTransaction();
            
            // Store who made the update
            $validatedData['updated_by'] = auth()->id();
            
            // Update ticket
            $ticket->update($validatedData);

            // Create feedback if provided
            if ($request->filled('feedback')) {
                DB::table('ticket_feedbacks')->insert([
                    'ticket_id' => $ticket->id,
                    'user_id' => auth()->id(),
                    'comment' => $request->feedback,
                    'status_change' => $request->status,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();
            return redirect()->route('support.dashboard')->with('success', 'Ticket updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update ticket: ' . $e->getMessage());
        }
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

    // Archive ticket
    // public function archiveTicket($id)
    // {
    //     try {
    //         $ticket = Ticket::findOrFail($id);
    //         $result = $ticket->update([
    //             'is_archived' => true
    //         ]);

    //         if ($result) {
    //             return redirect()->back()->with('success', 'Ticket has been archived successfully');
    //         }
            
    //         return redirect()->back()->with('error', 'Failed to archive ticket');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Failed to archive ticket: ' . $e->getMessage());
    //     }
    // }

    // Unarchive ticket
    // public function unarchiveTicket($id)
    // {
    //     try {
    //         $ticket = Ticket::findOrFail($id);
    //         $result = $ticket->update([
    //             'is_archived' => false
    //         ]);

    //         if ($result) {
    //             return redirect()->back()->with('success', 'Ticket has been unarchived successfully');
    //         }
            
    //         return redirect()->back()->with('error', 'Failed to unarchive ticket');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Failed to unarchive ticket: ' . $e->getMessage());
    //     }
    // }
}