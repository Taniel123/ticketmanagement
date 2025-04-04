@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-gray-900">User Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500">View and manage your support tickets</p>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Quick Actions</h2>
                        <p class="mt-1 text-sm text-gray-500">Create and manage your tickets</p>
                    </div>
                    <a href="{{ route('tickets.create') }}" 
                        class="bg-indigo-500 hover:bg-indigo-600 text-white py-2 px-4 rounded text-sm font-medium transition-colors">
                        Create New Ticket
                    </a>
                </div>
            </div>
        </div>

        <!-- Tickets List -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Your Tickets</h2>
                
                @if($tickets->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($tickets as $ticket)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                <div class="p-5">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $ticket->title }}</h3>
                                    <p class="mt-2 text-sm text-gray-500">{{ Str::limit($ticket->description, 100) }}</p>
                                    <div class="mt-4 flex justify-between items-center">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $ticket->status == 'open' ? 'bg-green-100 text-green-800' : 
                                               ($ticket->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst($ticket->status) }}
                                        </span>
                                        <a href="{{ route('tickets.show', $ticket) }}" 
                                            class="text-sm text-indigo-500 hover:text-indigo-600 font-medium">
                                            View Details →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-500">No tickets found. Create your first ticket to get started.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection