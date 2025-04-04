<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{


    // Show the create ticket form
    public function create()
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login'); // Redirect to login if not authenticated
        }

        return view('tickets.create');
    }

    // Store the ticket
    public function store(Request $request)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login'); // Redirect to login if not authenticated
        }

        // Validate the incoming request
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high', // Validate priority
        ]);

        

        // Create a new ticket and associate it with the authenticated user
        $ticket = new Ticket();
        $ticket->title = $validatedData['title'];
        $ticket->description = $validatedData['description'];
        $ticket->user_id = Auth::id(); // Store the ticket for the authenticated user
        $ticket->created_by = auth()->user()->id;
        $ticket->priority = $validatedData['priority']; // Save priority
        $ticket->status = 'open'; // ✅ Default
        $ticket->save();

        // Redirect to the user's tickets list (or anywhere else)
        return redirect()->route('user.dashboard')->with('success', 'Ticket created successfully!');
    }

    public function updateStatus(Request $request, $id)
{
    if (!Auth::check() || !in_array(auth()->user()->role, ['support', 'admin'])) {
        return redirect()->back()->with('error', 'Unauthorized access.');
    }

    $request->validate([
        'status' => 'required|in:open,ongoing,closed',
    ]);

    $ticket = Ticket::findOrFail($id);
    $ticket->status = $request->status;
    $ticket->save();

    return redirect()->back()->with('success', 'Ticket status updated successfully.');
}

}
