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
                $tickets = Ticket::with('user')->latest()->paginate(3);
                break;

            case 'support':
                $tickets = Ticket::with('user')->whereIn('status', ['open', 'ongoing'])->latest()->paginate(3);
                break;

            default:
                $tickets = Ticket::where('user_id', Auth::id())->latest()->paginate(3);
                break;
        }

        return view('tickets.index', compact('tickets'));
    }

    // Show create form
    public function create()
    {
        return view('user.tickets.create');
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

        // Send notifications
        $ticket->user->notify(new TicketNotification($ticket, 'created', auth()->user()));
        
        $supportUsers = User::where('role', 'support')->get();
        foreach ($supportUsers as $supportUser) {
            $supportUser->notify(new TicketNotification($ticket, 'new_ticket_for_support', auth()->user()));
        }

        // Redirect to user dashboard with success message
        return redirect()->route('user.dashboard')
            ->with('success', 'Ticket created successfully!');
    }

    // Show individual ticket
    public function show(Ticket $ticket)
    {
        if (Auth::user()->role === 'user' && $ticket->user_id !== Auth::id()) {
            return redirect()->route('tickets.index')->with('error', 'You are not authorized to view this ticket.');
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

            $oldStatus = $ticket->status;

            $ticket->update(['status' => $request->status]);

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
            return redirect()->back()->with('error', 'Failed to update ticket status: ' . $e->getMessage());
        }
    }

    // Edit ticket
    public function edit(Ticket $ticket)
    {
        if (!in_array(auth()->user()->role, ['admin', 'support'])) {
            abort(403);
        }

        return view('tickets.edit', compact('ticket'));
    }

    // Update ticket (admin & support)
    public function update(Request $request, Ticket $ticket)
    {
        // Verify user owns this ticket
        if ($ticket->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high'
        ]);

        try {
            $ticket->update($validated);
            
            return redirect()->route('tickets.show', $ticket)
                ->with('success', 'Ticket updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to update ticket: ' . $e->getMessage());
        }
    }

    // Admin-specific update method
    public function adminUpdate(Request $request, Ticket $ticket)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:open,ongoing,closed',
            'priority' => 'required|in:low,medium,high',
            'feedback' => 'required_if:status,ongoing,closed'
        ]);

        try {
            DB::beginTransaction();
            
            $oldStatus = $ticket->status;
            $statusChanged = $oldStatus !== $request->status;

            $ticket->update($validatedData);

            // Create feedback if status changed and feedback provided
            if ($request->filled('feedback')) {
                $ticket->feedbacks()->create([
                    'user_id' => auth()->id(),
                    'comment' => $request->feedback,
                    'status_change' => $request->status,
                    'created_at' => now()
                ]);

                // Notify user of update
                $ticket->user->notify(new TicketUpdateNotification(
                    $ticket,
                    $oldStatus,
                    $request->status,
                    $request->feedback
                ));
            }

            DB::commit();
            return redirect()->route('admin.dashboard')
                ->with('success', 'Ticket updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to update ticket: ' . $e->getMessage());
        }
    }

    public function supportEdit(Ticket $ticket)
    {
        // Check if user is support
        if (auth()->user()->role !== 'support') {
            abort(403, 'Unauthorized action.');
        }

        return view('support.tickets.edit', compact('ticket'));
    }

    public function supportUpdate(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,ongoing,closed',
            'priority' => 'required|in:low,medium,high',
            'feedback' => [
                'required_if:status,ongoing',
                'required_if:status,closed',
                'string',
                
            ]
        ]);

        // ...rest of your update logic...

        try {
            DB::beginTransaction();
            
            $oldStatus = $ticket->status;
            $ticket->update($validated);

            // Create feedback if provided
            if ($request->filled('feedback')) {
                $ticket->feedbacks()->create([
                    'user_id' => auth()->id(),
                    'comment' => $request->feedback,
                    'status_change' => $request->status
                ]);
            }

            DB::commit();
            return redirect()->route('support.dashboard')
                ->with('success', 'Ticket updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to update ticket: ' . $e->getMessage());
        }
    }

    // Delete ticket (admin only)
    public function destroy(Ticket $ticket)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
    }

    public function supportShow(Ticket $ticket)
    {
        // Check if user is support
        if (auth()->user()->role !== 'support') {
            abort(403, 'Unauthorized action.');
        }

        // Load relationships
        $ticket->load(['user', 'feedbacks' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        return view('support.tickets.show', compact('ticket'));
    }

    public function userShow(Ticket $ticket)
    {
        // Check if user owns this ticket
        if ($ticket->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Update to eager load feedbacks with their users
        $ticket->load([
            'feedbacks' => function ($query) {
                $query->with('user')->orderBy('created_at', 'desc');
            }
        ]);

        return view('user.tickets.show', compact('ticket'));
    }

   
    public function adminShow(Ticket $ticket)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Load relationships
        $ticket->load([
            'user',
            'feedbacks' => function ($query) {
                $query->with('user')->latest();
            }
        ]);

        // Get ticket history
        $history = [
            'created' => [
                'user' => $ticket->user->name,
                'time' => $ticket->created_at
            ],
            'updated' => [
                'user' => $ticket->updatedBy ? $ticket->updatedBy->name : 'N/A',
                'time' => $ticket->updated_at
            ]
        ];

        return view('admin.tickets.show', compact('ticket', 'history'));
    }

  
    public function adminEdit(Ticket $ticket)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.tickets.edit', compact('ticket'));
    }

    public function adminCreate()
    {
        return view('admin.tickets.create');
    }

    public function adminStore(Request $request)
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
            'user_id' => auth()->id(),
            'created_by' => auth()->id()
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Ticket created successfully');
    }
}
