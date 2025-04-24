@extends('layouts.support_app')

@section('content')
    <div class="py-10 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Support Dashboard</h2>
                    </div>

                    <!-- Status Filter Buttons -->
                    <div class="flex space-x-4 mb-6">

                        <a href="{{ route('support.dashboard', ['status' => 'open']) }}"
                            class="inline-flex items-center px-4 py-2 rounded-md {{ $status === 'open' ? 'bg-green-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                            <span>Open</span>
                            <span
                                class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $status === 'open' ? 'bg-green-500 text-white' : 'bg-green-100 text-green-600' }}">
                                {{ $openCount }}
                            </span>
                        </a>

                        <a href="{{ route('support.dashboard', ['status' => 'ongoing']) }}"
                            class="inline-flex items-center px-4 py-2 rounded-md {{ $status === 'ongoing' ? 'bg-yellow-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                            <span>Ongoing</span>
                            <span
                                class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $status === 'ongoing' ? 'bg-yellow-500 text-white' : 'bg-yellow-100 text-yellow-600' }}">
                                {{ $ongoingCount }}
                            </span>
                        </a>

                        <a href="{{ route('support.dashboard', ['status' => 'closed']) }}"
                            class="inline-flex items-center px-4 py-2 rounded-md {{ $status === 'closed' ? 'bg-red-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                            <span>Closed</span>
                            <span
                                class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $status === 'closed' ? 'bg-red-500 text-white' : 'bg-red-100 text-red-600' }}">
                                {{ $closedCount }}
                            </span>
                        </a>

                        <a href="{{ route('support.dashboard', ['status' => 'all']) }}"
                            class="inline-flex items-center px-4 py-2 rounded-md {{ $status === 'all' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                            <span>All Tickets</span>
                            <span
                                class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $status === 'all' ? 'bg-indigo-500 text-white' : 'bg-gray-100' }}">
                                {{ $openCount + $ongoingCount + $closedCount }}
                            </span>
                        </a>
                    </div>



                    <!-- Tickets Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Title</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        User</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($tickets as $ticket)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->title }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $ticket->status === 'open' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $ticket->status === 'ongoing' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $ticket->status === 'closed' ? 'bg-red-100 text-red-800' : '' }}">
                                                {{ ucfirst($ticket->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-4">
                                                <a href="{{ route('tickets.show', $ticket->id) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">View</a>

                                                <a href="{{ route('support.tickets.edit', $ticket->id) }}"
                                                    class="text-green-600 hover:text-green-900">Edit</a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            No tickets found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $tickets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
