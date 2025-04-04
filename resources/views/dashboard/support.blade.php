@extends('layouts.app')

@section('content')
    <h1>Support Dashboard</h1>
    
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Priority</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($tickets as $ticket)
    <div class="ticket">
        <h3>{{ $ticket->title }}</h3>
        <p>{{ $ticket->description }}</p>
        <p>Priority: {{ $ticket->priority }}</p>
        <p>Status: {{ $ticket->status }}</p>

        <!-- Update status form -->
        <form action="{{ route('tickets.updateStatus', $ticket->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <select name="status">
                <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                <option value="ongoing" {{ $ticket->status == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
            <button type="submit">Update</button>
        </form>
    </div>
@endforeach
        </tbody>
    </table>
@endsection
