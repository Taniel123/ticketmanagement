@extends('layouts.support_app')

@section('content')
<!-- Breadcrumb -->
<div class="flex items-center text-sm text-gray-500 mb-4 mt-4">
    <a href="{{ route('support.dashboard') }}" class="hover:text-indigo-600 transition-colors duration-200">Dashboard</a>
    <svg class="h-5 w-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
    </svg>
    <span>Support Tickets</span>
</div>

<!-- Stats Section -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <!-- Open Tickets -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 mr-4">
                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Open Tickets</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $tickets->where('status', 'Open')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Ongoing Tickets -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 mr-4">
                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Ongoing Tickets</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $tickets->where('status', 'Ongoing')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Closed Tickets -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-gray-100 mr-4">
                <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Closed Tickets</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $tickets->where('status', 'Closed')->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <h1 class="text-lg font-medium text-gray-800 flex items-center">
                <span class="w-1.5 h-6 bg-indigo-600 rounded mr-3"></span>
                Support Tickets
            </h1>

            <!-- Enhanced Filter Form -->
            <form id="filterForm" method="GET" action="{{ route('support.dashboard') }}"
                class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                <div class="flex items-center space-x-3">
                    <select name="status"
                        class="px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 min-w-[120px]">
                        <option value="">All Status</option>
                        <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                        <option value="Ongoing" {{ request('status') == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>

                    <select name="priority"
                        class="px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 min-w-[120px]">
                        <option value="">All Priorities</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    </select>

                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Search tickets..." 
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($tickets as $ticket)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#{{ $ticket->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $ticket->title }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-xs font-medium text-indigo-600">
                                        {{ strtoupper(substr($ticket->user->name, 0, 2)) }}
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $ticket->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $ticket->created_at->format('M d, Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $ticket->status === 'Open' ? 'bg-green-100 text-green-800' : 
                                   ($ticket->status === 'Ongoing' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                <span class="h-1.5 w-1.5 mr-1.5 rounded-full 
                                    {{ $ticket->status === 'Open' ? 'bg-green-600' : 
                                       ($ticket->status === 'Ongoing' ? 'bg-blue-600' : 'bg-gray-600') }}"></span>
                                {{ $ticket->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $ticket->priority === 'high' ? 'bg-red-100 text-red-800' : 
                                   ($ticket->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                <span class="h-1.5 w-1.5 mr-1.5 rounded-full 
                                    {{ $ticket->priority === 'high' ? 'bg-red-600' : 
                                       ($ticket->priority === 'medium' ? 'bg-yellow-600' : 'bg-green-600') }}"></span>
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                <a href="{{ route('tickets.show', $ticket->id) }}"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View
                                    </a>
                                    <a href="{{ route('tickets.edit', $ticket->id) }}"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                </div>
                            </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <h3 class="text-sm font-medium text-gray-900">No tickets found</h3>
                                <p class="mt-1 text-sm text-gray-500">No tickets match your current filters.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
        <!-- Table Footer/Pagination -->
        <div class="px-6 py-5 bg-gray-50 border-t border-gray-200 text-xs text-gray-600 flex justify-between items-center">
            <div>
                Showing {{ $tickets->firstItem() ?? 0 }}–{{ $tickets->lastItem() ?? 0 }} of {{ $tickets->total() }} ticket(s)
            </div>
            <div class="text-right">
                <div class="inline-flex items-center space-x-1">
                    @if ($tickets->onFirstPage())
                        <span
                            class="px-3 py-1 text-xs text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">Previous</span>
                    @else
                        <a href="{{ $tickets->previousPageUrl() }}"
                            class="px-3 py-1 text-xs text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50">Previous</a>
                    @endif

                    @foreach ($tickets->getUrlRange(1, $tickets->lastPage()) as $page => $url)
                        @if ($page == $tickets->currentPage())
                            <span
                                class="px-3 py-1 text-xs text-white bg-indigo-600 border border-indigo-600 rounded-md">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="px-3 py-1 text-xs text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($tickets->hasMorePages())
                        <a href="{{ $tickets->nextPageUrl() }}"
                            class="px-3 py-1 text-xs text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50">Next</a>
                    @else
                        <span
                            class="px-3 py-1 text-xs text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">Next</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Viewing/Editing Ticket -->
    <div id="openTicketModal" class="fixed inset-0 bg-black/30 z-50 hidden items-center justify-center backdrop-blur-xs">
        <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-xl">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Update Ticket Details</h2>

            <form id="ticketForm" method="POST">
                @csrf
                @method('PATCH')

                <!-- Title -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Title:</label>
                    <p id="modalTitle" class="text-gray-900 mt-1 text-sm font-medium"></p>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Description:</label>
                    <p id="modalDescription" class="text-gray-800 mt-1 text-sm"></p>
                </div>

                <!-- Priority -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Priority:</label>
                    <p id="modalPriority" class="text-gray-900 mt-1 text-sm font-medium"></p>
                </div>

                <!-- Created By -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Created By:</label>
                    <p id="modalUser" class="text-gray-900 mt-1 text-sm font-medium"></p>
                </div>

                <!-- Status Dropdown -->
                <div class="mb-6">
                    <label for="modalStatus" class="block text-sm font-medium text-gray-700">Status:</label>
                    <select name="status" id="modalStatus"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="Open">Open</option>
                        <option value="Ongoing">Ongoing</option>
                        <option value="Closed">Closed</option>
                    </select>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end gap-4">
                    <button type="button" onclick="closeTicketModal()"
                        class="text-gray-700 bg-gray-200 hover:bg-gray-300 px-6 py-2 rounded-md text-sm font-medium">Cancel</button>
                    <button type="submit"
                        class="bg-indigo-600 text-white hover:bg-indigo-700 px-6 py-2 rounded-md text-sm font-semibold">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Keep the feedback modal include if needed -->
    @foreach ($tickets as $ticket)
        @include('components.feedback-modal', ['ticket' => $ticket])
    @endforeach
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('filterForm');

            // Auto-submit on dropdown change
            form.querySelectorAll('select').forEach(select => {
                select.addEventListener('change', () => {
                    form.submit();
                });
            });

            // Auto-submit on Enter key in search input
            const searchInput = form.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        form.submit();
                    }
                });
            }
        });

        function openTicketModal(ticketId, title, description, priority, userName, status) {
            // Populate modal fields
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalDescription').textContent = description;
            document.getElementById('modalPriority').textContent = priority;
            document.getElementById('modalUser').textContent = userName;
            document.getElementById('modalStatus').value = status;

            // Update form action URL
            const form = document.getElementById('ticketForm');
            form.action = `/support/tickets/${ticketId}/status`;

            // Show modal
            const modal = document.getElementById('openTicketModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeTicketModal() {
            const modal = document.getElementById('openTicketModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endsection