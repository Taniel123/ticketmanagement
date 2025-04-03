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
    </div>
@endsection
