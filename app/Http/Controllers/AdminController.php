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
         // Fetch pending users who are not approved AND not archived
         $pendingUsers = User::where('is_approved', false)
                             ->where('is_archived', false)
                             ->paginate(10);
     
         return view('dashboard.pending-users', compact('pendingUsers'));
     }
public function archiveUsers()
{
    // Fetch archived users
    $archivedUsers = User::where('is_archived', true)->paginate(10);

    return view('dashboard.archive-users', compact('archivedUsers'));
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
        // If the status is 'active', filter by 1 (true), otherwise by 0 (false)
        $query->where('status', $request->status == 'active' ? 1 : 0);
    }
    

    $users = $query->paginate(10);

    return view('dashboard.manage-roles', compact('users'));
}
public function manageTickets(Request $request)
{
    $query = Ticket::query();

    // Apply search filter (search by title or any other field)
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%'); // or another field like ticket ID
    }

    // Apply status filter
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Apply priority filter (if you have it)
    if ($request->filled('priority')) {
        $query->where('priority', $request->priority);
    }

    // Apply date filters (if you want)
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
    }

    // Fetch tickets with pagination
    $tickets = $query->paginate(10);

    return view('dashboard.manage-tickets', compact('tickets'));
}

}
