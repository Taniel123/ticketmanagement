<!-- resources/views/dashboard/user.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="user-dashboard">
        <h2>User Dashboard</h2>
        <p>Welcome, {{ auth()->user()->name }}! You have user privileges.</p>
        
        <!-- Button to create a new ticket -->
        <a href="{{ route('tickets.create') }}" class="btn btn-primary">Create New Ticket</a>

        <!-- Display tickets associated with the user -->
        <h3>Your Tickets</h3>
        <ul>
            @forelse($tickets as $ticket)
                <li>
                    <strong>{{ $ticket->title }}</strong><br>
                    <em>{{ $ticket->description }}</em><br>
                    <span>Priority: {{ ucfirst($ticket->priority) }}</span><br>
                    <span>Status: {{ ucfirst($ticket->status) }}</span>
                </li>
            @empty
                <li>No tickets found. Create a new ticket!</li>
            @endforelse
        </ul>
    </div>
@endsection
