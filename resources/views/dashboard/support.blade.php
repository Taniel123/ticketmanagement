@extends('layouts.support_app')

@section('content')

    <div class="py-6">
        <!-- Main Card -->
        <div
            class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-800 flex items-center">
                    <span class="w-1 h-5 bg-indigo-600 rounded mr-2"></span>
                    Manage Tickets
                </h2>

                <!-- Filters and Search -->
                <form method="GET" action="{{ request()->url() }}"
                    class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
                    <!-- Status Dropdown -->
                    <select name="status"
                        class="px-3 py-2 border border-gray-300 rounded shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500"
                        onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="Open" {{ request('status') === 'Open' ? 'selected' : '' }}>Open</option>
                        <option value="Ongoing" {{ request('status') === 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="Closed" {{ request('status') === 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>

                    <!-- Priority Dropdown -->
                    <select name="priority"
                        class="px-3 py-2 border border-gray-300 rounded shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500"
                        onchange="this.form.submit()">
                        <option value="">All Priorities</option>
                        <option value="High" {{ request('priority') === 'High' ? 'selected' : '' }}>High</option>
                        <option value="Medium" {{ request('priority') === 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="Low" {{ request('priority') === 'Low' ? 'selected' : '' }}>Low</option>
                    </select>

                    <!-- Search Input -->
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title..."
                        class="w-full sm:w-64 px-3 py-2 border border-gray-300 rounded shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500"
                        oninput="clearTimeout(this.delay); this.delay = setTimeout(() => this.form.submit(), 500)">
                </form>

            </div>

            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created by
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($tickets as $ticket)
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-gray-900">{{ $ticket->title }}</div>
                                                <div class="text-xs text-gray-500 mt-1">{{ Str::limit($ticket->description, 60) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                            {{ $ticket->status == 'Open' ? 'bg-emerald-100 text-emerald-800' :
                            ($ticket->status == 'Ongoing' ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-100 text-slate-800') }}">
                                                    <span class="h-1.5 w-1.5 mr-1.5 rounded-full 
                                                            {{ $ticket->status == 'Open' ? 'bg-emerald-600' :
                            ($ticket->status == 'Ongoing' ? 'bg-indigo-600' : 'bg-slate-600') }}"></span>
                                                    {{ ucfirst($ticket->status) }}
                                                </span>

                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                            {{ $ticket->priority == 'High' ? 'bg-red-100 text-red-800' :
                            ($ticket->priority == 'Medium' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                                    <span class="h-1.5 w-1.5 mr-1.5 rounded-full 
                                                            {{ $ticket->priority == 'High' ? 'bg-red-600' :
                            ($ticket->priority == 'Medium' ? 'bg-amber-600' : 'bg-blue-600') }}"></span>
                                                    {{ ucfirst($ticket->priority) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                                {{ $ticket->user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                                <div class="flex items-center space-x-3">

                                                    <button type="button" onclick="openTicketModal(
                                                {{ $ticket->id }},
                                                '{{ addslashes($ticket->title) }}',
                                                '{{ addslashes($ticket->description) }}',
                                                '{{ $ticket->priority }}',
                                                '{{ addslashes($ticket->user->name) }}',
                                                '{{ $ticket->status }}'
                                            )" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-xs font-medium transition-colors duration-200 shadow-sm">
                                                        Edit
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="h-8 w-8 mb-2 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <p class="text-sm font-medium">No tickets found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer/Pagination -->
            <div
                class="px-6 py-5 bg-gray-50 border-t border-gray-200 text-xs text-gray-600 flex justify-between items-center">
                <div>
                    Showing {{ $tickets->firstItem() }}–{{ $tickets->lastItem() }} of {{ $tickets->total() }} ticket(s)
                </div>
                <div class="text-right">
                    <div class="inline-flex items-center space-x-1">
                        {{-- Previous Page Link --}}
                        @if ($tickets->onFirstPage())
                            <span
                                class="px-3 py-1 text-xs text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">Previous</span>
                        @else
                            <a href="{{ $tickets->previousPageUrl() }}"
                                class="px-3 py-1 text-xs text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50">Previous</a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($tickets->getUrlRange(1, $tickets->lastPage()) as $page => $url)
                            @if ($page == $tickets->currentPage())
                                <span
                                    class="px-3 py-1 text-xs text-white bg-indigo-600 border border-indigo-600 rounded-md">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                    class="px-3 py-1 text-xs text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
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
    </div>
@endsection

<div id="openTicketModal"
    class="fixed inset-0 bg-black/30 z-50 hidden flex items-center justify-center backdrop-blur-xs">
    <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-xl">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Update Ticket Details</h2>

        <form id="ticketForm" method="POST">
            @csrf
            @method('PATCH')

            <!-- Ticket ID -->
            <input type="hidden" id="ticketId" name="ticketId" />

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



@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');

            // Auto-submit on dropdown change for filters (NOT for modal)
            form.querySelectorAll('select').forEach(select => {
                select.addEventListener('change', (event) => {
                    // Ignore auto-submit for the modal's status dropdown
                    if (event.target.id !== 'modalStatus') {
                        form.submit();
                    }
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
        function openTicketModal(id, title, description, priority, user, status) {
            const modal = document.getElementById('openTicketModal');

            // Check if the modal is found
            if (!modal) {
                console.error("Modal element not found!");
                return;
            }

            // Set modal visibility
            modal.classList.remove('hidden');

            // Populate modal fields
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalDescription').innerText = description;
            document.getElementById('modalPriority').innerText = priority;
            document.getElementById('modalUser').innerText = user;
            document.getElementById('modalStatus').value = status;

            // Set ticket ID for form submission (hidden input for ticket ID)
            document.getElementById('ticketId').value = id;

            // Set form action (you should set this to the URL that handles the ticket update, e.g., /tickets/{id})
            document.getElementById('ticketForm').action = `/support/tickets/${id}/status`;
        }

        // Function to close the modal
        function closeTicketModal() {
            const modal = document.getElementById('openTicketModal');

            if (!modal) {
                console.error("Modal element not found!");
                return;
            }

            modal.classList.add('hidden');
        }


    </script>
@endsection