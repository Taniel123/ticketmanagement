@extends('layouts.admin_app')


@section('content')
    <!-- Tickets Management Section -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mt-6 overflow-hidden hover:shadow-md transition-shadow duration-300">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 w-full">
                <!-- Left Title -->
                <h1 class="text-lg font-medium text-gray-800 flex items-center">
                <span class="w-1 h-5 bg-gray-600 rounded mr-2"></span>
                    Manage Tickets
                </h1>

                <!-- Filters and Search -->
                <form id="filterForm" method="GET" action="{{ route('admin.manage-tickets') }}" class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
                    <!-- Status Dropdown -->
                    <select name="status" class="px-3 py-2 border border-gray-300 rounded shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Status</option>
                        <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                        <option value="Ongoing" {{ request('status') == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>

                    <!-- Priority Dropdown -->
                    <select name="priority" class="px-3 py-2 border border-gray-300 rounded shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Priorities</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    </select>

                    <!-- Search Input (aligned to the right) -->
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by ticket title..." 
                        class="w-full sm:w-64 px-3 py-2 border border-gray-300 rounded shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 ml-auto">
                </form>

            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($tickets ?? [] as $ticket)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">{{ $ticket->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $ticket->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ticket->status === 'open' ? 'bg-green-100 text-green-800' : ($ticket->status === 'ongoing' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                <span class="h-1.5 w-1.5 mr-1.5 rounded-full {{ $ticket->status === 'open' ? 'bg-green-600' : ($ticket->status === 'ongoing' ? 'bg-blue-600' : 'bg-gray-600') }}"></span>
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ticket->priority === 'High' ? 'bg-red-100 text-red-800' : ($ticket->priority === 'Medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                <span class="h-1.5 w-1.5 mr-1.5 rounded-full {{ $ticket->priority === 'High' ? 'bg-red-600' : ($ticket->priority === 'Medium' ? 'bg-yellow-600' : 'bg-green-600') }}"></span>
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap space-x-2">
                            <div class="flex items-center space-x-3">
                            <a href="{{ route('tickets.show', $ticket) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-xs font-medium transition-colors duration-200 shadow-sm">View</a>
                            <button
    type="button"
    onclick="openTicketModal(
        {{ $ticket->id }},
        '{{ addslashes($ticket->title) }}',
        '{{ addslashes($ticket->description) }}',
        '{{ $ticket->priority }}',
        '{{ addslashes($ticket->user->name) }}',
        '{{ $ticket->status }}'
    )"
    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-xs font-medium transition-colors duration-200 shadow-sm"
>
    Edit
</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @if(empty($tickets) || count($tickets) === 0)
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="h-8 w-8 mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm font-medium">No tickets found</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Table Footer/Pagination -->
        <div class="px-6 py-5 bg-gray-50 border-t border-gray-200 text-xs text-gray-600 flex justify-between items-center">
            <div>
                Showing {{ $tickets->firstItem() }}–{{ $tickets->lastItem() }} of {{ $tickets->total() }} ticket(s)
            </div>
            <div class="text-right">
                <div class="inline-flex items-center space-x-1">
                    @if ($tickets->onFirstPage())
                    <span class="px-3 py-1 text-xs text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">Previous</span>
                    @else
                    <a href="{{ $tickets->previousPageUrl() }}" class="px-3 py-1 text-xs text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50">Previous</a>
                    @endif

                    @foreach ($tickets->getUrlRange(1, $tickets->lastPage()) as $page => $url)
                    @if ($page == $tickets->currentPage())
                    <span class="px-3 py-1 text-xs text-white bg-indigo-600 border border-indigo-600 rounded-md">{{ $page }}</span>
                    @else
                    <a href="{{ $url }}" class="px-3 py-1 text-xs text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50">{{ $page }}</a>
                    @endif
                    @endforeach

                    @if ($tickets->hasMorePages())
                    <a href="{{ $tickets->nextPageUrl() }}" class="px-3 py-1 text-xs text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50">Next</a>
                    @else
                    <span class="px-3 py-1 text-xs text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">Next</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Modal for Viewing Ticket -->
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
                <select name="status" id="modalStatus" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
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
    form.action = `/admin/tickets/${ticketId}/status`;

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


