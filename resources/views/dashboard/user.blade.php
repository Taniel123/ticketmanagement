@extends('layouts.app')

@section('content')
    <div class="user-dashboard">
        <h2>User Dashboard</h2>
        <p>Welcome, {{ auth()->user()->name }}! You have user privileges.</p>
        
        <!-- Button to create a new ticket -->
        <a href="{{ route('tickets.create') }}" class="btn btn-primary mb-4">Create New Ticket</a>

        <!-- Display tickets associated with the user -->
        <h3>Your Tickets</h3>

        @if($tickets->isEmpty())
            <p>No tickets found. Create a new ticket!</p>
        @else
            <div class="ticket-list">
                @foreach($tickets as $ticket)
                    <div class="ticket-card mb-3 p-3 border rounded shadow-sm">
                        <h4 class="mb-1">{{ $ticket->title }}</h4>
                        <p class="text-muted">{{ $ticket->description }}</p>

                        <p>
                            <strong>Priority:</strong> {{ ucfirst($ticket->priority) }} <br>
                            <strong>Status:</strong> 
                            <span class="badge 
                                @if($ticket->status === 'open') bg-success 
                                @elseif($ticket->status === 'ongoing') bg-warning 
                                @else bg-secondary 
                                @endif">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
