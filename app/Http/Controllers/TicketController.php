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
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Add this

class TicketController extends Controller
{
    use AuthorizesRequests; // Add this trait

    public function index(Request $request)
    {
        // Get all tickets for statistics
        $allTickets = Ticket::where('user_id', auth()->id())->get();
        
        // Get paginated tickets for display
        $query = Ticket::where('user_id', auth()->id());
        
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'ongoing') {
                $query->where(function($q) {
                    $q->where('status', 'ongoing')
                      ->orWhere('status', 'in_progress');
                });
            } else {
                $query->where('status', $status);
            }
        }
    
        $tickets = $query->latest()
                        ->paginate(10)
                        ->withQueryString();
    
        return view('dashboard.user', [
            'tickets' => $tickets,
            'allTickets' => $allTickets
        ]);
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
    
            // Check if the request is AJAX (for modal)
            if ($request->ajax()) {
                // Return error message in case of AJAX request
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update ticket status: ' . $e->getMessage()
                ]);
            }
    
            // If not AJAX, return back with error
            return back()->with('error', 'Failed to update ticket status: ' . $e->getMessage());
        }
    }

    public function edit(Ticket $ticket)
    {
        $this->authorize('update', $ticket); // Uses TicketPolicy@update
        return view('tickets.edit', compact('ticket'));
    }
    
    public function update(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket); // Uses TicketPolicy@update
    
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:open,ongoing,closed',
            'feedback' => 'required_if:status,ongoing,closed'
        ]);
    
        try {
            DB::beginTransaction();
    
            // Add info about who updated the ticket
            $validated['updated_by'] = auth()->id();
    
            // Update the ticket
            $ticket->update($validated);
    
            // If feedback is present, insert it into the feedback table
            if ($request->filled('feedback')) {
                DB::table('ticket_feedbacks')->insert([
                    'ticket_id' => $ticket->id,
                    'user_id' => auth()->id(),
                    'comment' => $request->feedback,
                    'status_change' => $request->status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
    
            DB::commit();
    
            return redirect()->route('support.dashboard')->with('success', 'Ticket updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update ticket: ' . $e->getMessage());
        }
    }
    
    public function adminUpdate(Request $request, Ticket $ticket)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
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
            
            // Store old status before update
            $oldStatus = $ticket->status;
            
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

            // Notify the ticket owner with all required parameters
            $ticket->user->notify(new TicketUpdateNotification(
                $ticket,
                $oldStatus,
                $validatedData['status'],
                auth()->user()
            ));

            DB::commit();
            return redirect()->route('admin.dashboard')
                ->with('success', 'Ticket updated successfully');
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

    public function adminEdit(Ticket $ticket)
    {
        $this->authorize('update', $ticket);
        
        // Return the admin edit view
        return view('dashboard.edit', compact('ticket'));
    }

    public function adminCreate()
{
    $users = User::where('role', 'user')->get();
    return view('admin.create_ticket', compact('users'));
}

public function adminStore(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'priority' => 'required|in:low,medium,high',
        'user_id' => 'required|exists:users,id'
    ]);

    $ticket = Ticket::create([
        'title' => $validated['title'],
        'description' => $validated['description'],
        'priority' => $validated['priority'],
        'status' => 'open',
        'user_id' => $validated['user_id'],
        'created_by' => auth()->id()
    ]);

    return redirect()
        ->route('admin.manage-tickets')
        ->with('success', 'Ticket created successfully');
}
    
    
}