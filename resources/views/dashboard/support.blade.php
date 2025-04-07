@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Support Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Overview and management of all user tickets</p>
    </div>

    <!-- Main Card -->
    <div class="bg-white shadow rounded-lg p-6">
        <!-- Table Header -->
        <div class="mb-4 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-700">All Tickets</h2>
            <span class="text-sm text-gray-500">{{ count($tickets) }} ticket(s) found</span>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $ticket->id }}</td>
                        <td class="px-6 py-4 text-sm">
                            <div class="font-medium text-gray-900">{{ $ticket->title }}</div>
                            <div class="text-xs text-gray-500">{{ Str::limit($ticket->description, 60) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-1 rounded-full
                                {{ $ticket->status == 'open' ? 'bg-green-100 text-green-700' :
                                   ($ticket->status == 'ongoing' ? 'bg-blue-100 text-blue-700' : 'bg-gray-200 text-gray-700') }}">
                                <svg class="w-3 h-3 fill-current" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-1 rounded-full
                                {{ $ticket->priority == 'high' ? 'bg-red-100 text-red-700' :
                                   ($ticket->priority == 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                <svg class="w-3 h-3 fill-current" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $ticket->user->name }}
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('tickets.update-status', $ticket) }}" method="POST" class="flex items-center space-x-2">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="text-sm bg-gray-100 border border-gray-300 rounded px-2 py-1 focus:outline-none">
                                    <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="ongoing" {{ $ticket->status === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                                <button type="submit" class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded transition">
                                    Update
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-sm text-gray-500">No tickets found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
