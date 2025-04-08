@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="mb-10">
        <h1 class="text-3xl font-bold text-gray-800">Support Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Overview and management of all user tickets</p>
    </div>

    <!-- Main Card -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
        <!-- Table Header -->
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-700">All Tickets</h2>
            <span class="text-sm bg-gray-100 text-gray-700 px-3 py-1 rounded-full font-medium">
                {{ count($tickets) }} ticket(s) found
            </span>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto rounded-xl border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">ID</th>
                        <th class="px-6 py-3 text-left font-semibold">Title</th>
                        <th class="px-6 py-3 text-left font-semibold">Status</th>
                        <th class="px-6 py-3 text-left font-semibold">Priority</th>
                        <th class="px-6 py-3 text-left font-semibold">User</th>
                        <th class="px-6 py-3 text-left font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-700">{{ $ticket->id }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $ticket->title }}</div>
                            <div class="text-xs text-gray-500">{{ Str::limit($ticket->description, 60) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full
                                {{ $ticket->status == 'open' ? 'bg-green-100 text-green-700' :
                                   ($ticket->status == 'ongoing' ? 'bg-blue-100 text-blue-700' : 'bg-gray-200 text-gray-700') }}">
                                <svg class="w-2.5 h-2.5 fill-current" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full
                                {{ $ticket->priority == 'high' ? 'bg-red-100 text-red-700' :
                                   ($ticket->priority == 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                <svg class="w-2.5 h-2.5 fill-current" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $ticket->user->name }}
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('tickets.update-status', $ticket) }}" method="POST" class="flex items-center space-x-2">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="text-sm bg-white border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                    <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="ongoing" {{ $ticket->status === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                                <button type="submit" class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded-md transition">
                                    Update
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500 text-sm bg-gray-50">
                            <div class="flex flex-col items-center">
                                <svg class="w-6 h-6 text-gray-400 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                No tickets found
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
