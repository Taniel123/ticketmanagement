@extends('layouts.app')

@section('content')
<div class="py-6">
    <!-- Header Section -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <div class="h-8 w-2 bg-indigo-600 rounded-full mr-3"></div>
                Create New Ticket
            </h1>
            <p class="text-sm text-gray-500 mt-1 ml-5">Fill in the details below to submit a new support ticket</p>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-all duration-300">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-medium text-gray-800 flex items-center">
                <span class="w-1 h-5 bg-indigo-600 rounded mr-2"></span>
                Ticket Details
            </h2>
        </div>
        
        <div class="p-6">
            <form action="{{ route('tickets.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Title Field -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Ticket Title</label>
                    <input type="text" name="title" id="title" 
                        class="mt-1 block w-full bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 rounded leading-tight focus:outline-none focus:bg-white focus:border-indigo-500 text-sm"
                        required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description Field -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Ticket Description</label>
                    <textarea name="description" id="description" rows="4" 
                        class="mt-1 block w-full bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 rounded leading-tight focus:outline-none focus:bg-white focus:border-indigo-500 text-sm"
                        required></textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority Dropdown -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700">Priority Level</label>
                    <select name="priority" id="priority" 
                        class="mt-1 block w-full bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 rounded leading-tight focus:outline-none focus:bg-white focus:border-indigo-500 text-sm"
                        required>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                    @error('priority')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                    <a href="{{ route('user.dashboard') }}" 
                        class="text-gray-500 hover:text-indigo-600 text-sm font-medium transition-colors duration-200 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Dashboard
                    </a>
                    <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded shadow-sm text-sm font-medium transition-colors duration-200 inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Create Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Notification Modal -->
<div id="notificationModal" style="display: none" class="fixed inset-0 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-20 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            New Ticket Created
                        </h3>
                        <div class="mt-2">
                            <p id="ticketInfo" class="text-sm text-gray-500">
                                Your new ticket has been successfully created!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeNotification()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                    Okay
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Show the notification modal
    function showNotification(ticketTitle) {
        const modal = document.getElementById('notificationModal');
        const ticketInfo = document.getElementById('ticketInfo');
        ticketInfo.innerHTML = 'Ticket Created: ' + ticketTitle;
        modal.style.display = 'block';
    }

    // Close the notification modal
    function closeNotification() {
        const modal = document.getElementById('notificationModal');
        modal.style.display = 'none';
        window.location.href = "{{ route('user.dashboard') }}"; // Redirect to the user dashboard
    }

    // Trigger notification if the session has the 'ticket_created' variable
    @if(session('ticket_created'))
        showNotification("{{ session('ticket_created') }}");
    @endif
</script>

@endsection