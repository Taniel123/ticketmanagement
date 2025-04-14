<?php

namespace App\Http\Controllers;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show the Pending Users page.
     */


    public function pendingUsers()
    {
     
         // Fetch pending users (users who are not approved)
    $pendingUsers = User::where('is_approved', false)->paginate(10);
        return view('dashboard.pending-users' , compact('pendingUsers'));
    }

    /**
     * Show the Manage Roles page.
     */
    public function manageRoles(Request $request)
{
    $query = User::query();

    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }

    if ($request->filled('status')) {
        $query->where('is_approved', $request->status);
    }

    $users = $query->paginate(10);

    return view('dashboard.manage-roles', compact('users'));
}


    /**
     * Show the Manage Tickets page.
     */
    public function manageTickets()
    {
        $tickets = Ticket::whereIn('status', ['open', 'in_progress'])->latest()->get();
        $tickets = Ticket::paginate(10);
        return view('dashboard.manage-tickets', compact('tickets'));
    }
}
