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

         // Fetch pending users who are not approved AND not archived
         $pendingUsers = User::where('is_approved', false)
                             ->where('is_archived', false)
                             ->paginate(10);

    $query = User::query()
        ->where('is_approved', true)
        ->where('is_archived', false); // Exclude archived users too

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

    return view('dashboard.manage-roles', compact('users', 'pendingUsers'));
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

public function updateRole(Request $request, User $user)
{
    $validated = $request->validate([
        'role' => 'required|in:admin,support,user'
    ]);

    try {
        $user->update([
            'role' => $validated['role']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User role updated successfully',
            'redirect' => route('admin.manage-roles')
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to update role'
        ], 500);
    }
}

public function index()
{
    // Get daily tickets for last 7 days
    $dailyTickets = Ticket::selectRaw('DATE(created_at) as date, COUNT(*) as count')
        ->whereDate('created_at', '>=', now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    // Get weekly tickets for last 4 weeks
    $weeklyTickets = Ticket::selectRaw('YEARWEEK(created_at) as week, COUNT(*) as count')
        ->whereDate('created_at', '>=', now()->subWeeks(4))
        ->groupBy('week')
        ->orderBy('week')
        ->get();

    // Get monthly tickets for last 6 months
    $monthlyTickets = Ticket::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
        ->whereDate('created_at', '>=', now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    return view('dashboard.admin', compact('dailyTickets', 'weeklyTickets', 'monthlyTickets'));
}

}



