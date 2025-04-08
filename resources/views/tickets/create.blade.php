@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-gray-900">Create New Ticket</h1>
            <p class="mt-1 text-sm text-gray-500">Fill in the details below to submit a new support ticket</p>
        </div>

        <!-- Form Section -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form action="{{ route('tickets.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Title Field -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Ticket Title</label>
                        <input type="text" name="title" id="title" 
                            class="mt-1 block w-full bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 text-sm"
                            required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description Field -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Ticket Description</label>
                        <textarea name="description" id="description" rows="4" 
                            class="mt-1 block w-full bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 text-sm"
                            required></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority Dropdown -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700">Priority Level</label>
                        <select name="priority" id="priority" 
                            class="mt-1 block w-full bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 text-sm"
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
                    <div class="flex justify-end">
                        <button type="submit" 
                            class="bg-indigo-500 hover:bg-indigo-600 text-white py-2 px-4 rounded text-sm font-medium transition-colors">
                            Create Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Notification Modal -->
<div id="notificationModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #f8f9fa; padding: 20px; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <h4>New Ticket Created</h4>
    <p id="ticketInfo">Your new ticket has been successfully created!</p>
    <button onclick="closeNotification()">Okay</button>
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
