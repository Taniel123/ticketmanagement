@extends('layouts.app')

@section('content')
    <div class="admin-dashboard">
        <h2>Admin Dashboard</h2>

        <h3>Pending Users</h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingUsers as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->is_approved ? 'Approved' : 'Pending' }}</td>
                        <td>
                            <!-- Approve Button -->
                            <form action="{{ route('admin.approve', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit">Approve</button>
                            </form>
                            
                            <!-- Delete Button -->
                            <form action="{{ route('admin.delete', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h3>Change User Roles</h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>
                            <!-- Role Change Form -->
                            <form action="{{ route('admin.changeRole', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <select name="role" required>
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="support" {{ $user->role == 'support' ? 'selected' : '' }}>Support</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <button type="submit">Change Role</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h3>All Tickets</h3>
        <ul>
            @forelse($tickets as $ticket)
                <li>
                    <strong>{{ $ticket->title }}</strong><br>
                    <em>{{ $ticket->description }}</em><br>
                    <span>Priority: {{ ucfirst($ticket->priority) }}</span><br>
                    <span>Status: {{ ucfirst($ticket->status) }}</span><br>
                    <span>Submitted by: {{ $ticket->user->name ?? 'Unknown' }}</span><br>

                    <!-- Update Status Form -->
                    <form action="{{ route('tickets.updateStatus', $ticket->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="status">
                            <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="ongoing" {{ $ticket->status == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                        <button type="submit">Update Status</button>
                    </form>
                </li>
            @empty
                <li>No tickets found.</li>
            @endforelse
        </ul>
    </div>
@endsection
