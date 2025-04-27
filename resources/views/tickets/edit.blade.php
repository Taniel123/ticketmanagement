@extends('layouts.support_app')

@section('content')
<div class="py-6 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="flex items-center text-sm text-gray-500 mb-6 space-x-2">
            <a href="{{ auth()->user()->role === 'admin' 
                ? route('admin.dashboard') 
                : (auth()->user()->role === 'support' 
                    ? route('support.dashboard') 
                    : route('user.dashboard')) }}" 
                class="hover:text-indigo-600 transition-colors duration-200">Dashboard</a>
            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <span>Edit Ticket</span>
        </div>

        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <span class="w-1.5 h-6 bg-indigo-600 mr-3 rounded"></span>
                Edit Ticket
            </h2>
            <p class="text-sm text-gray-500 mt-1">Update the information below to modify this support ticket</p>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="border-b border-gray-200 px-6 py-4 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-800 flex items-center">
                    <span class="w-1.5 h-5 bg-indigo-600 mr-2 rounded"></span>
                    Ticket Details
                </h3>
            </div>

            <form method="POST" 
                action="{{ auth()->user()->role === 'admin' 
                    ? route('admin.tickets.update', $ticket) 
                    : (auth()->user()->role === 'support' 
                        ? route('support.tickets.update', $ticket) 
                        : route('tickets.update', $ticket)) }}" 
                class="p-6 space-y-6">
                @csrf
                @method('PATCH')

                <!-- Title -->
                <div class="space-y-1">
                    <label for="title" class="block text-sm font-medium text-gray-700">
                        Ticket Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                        name="title" 
                        id="title" 
                        value="{{ old('title', $ticket->title) }}" 
                        required
                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="space-y-1">
                    <label for="description" class="block text-sm font-medium text-gray-700">
                        Ticket Description <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="description" 
                        id="description" 
                        rows="4" 
                        required
                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">{{ old('description', $ticket->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority & Status Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Priority -->
                    <div class="space-y-1">
                        <label for="priority" class="block text-sm font-medium text-gray-700">
                            Priority Level <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="priority" 
                            id="priority" 
                            required
                            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
                            @foreach(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $value => $label)
                                <option value="{{ $value }}" {{ old('priority', $ticket->priority) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('priority')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="space-y-1">
                        <label for="status" class="block text-sm font-medium text-gray-700">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="status" 
                            id="status" 
                            required
                            onchange="toggleFeedback(this.value)"
                            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
                            @foreach(['open' => 'Open', 'ongoing' => 'Ongoing', 'closed' => 'Closed'] as $value => $label)
                                <option value="{{ $value }}" {{ old('status', $ticket->status) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Feedback Section -->
                <div id="feedbackSection" class="space-y-1">
                    <label for="feedback" class="block text-sm font-medium text-gray-700">
                        Feedback
                        <span id="feedbackRequired" class="text-red-500 text-xs ml-1 hidden">*</span>
                    </label>
                    <textarea 
                        name="feedback" 
                        id="feedback" 
                        rows="3"
                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out"
                        placeholder="Add your feedback here...">{{ old('feedback', $ticket->feedback) }}</textarea>
                    @error('feedback')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ auth()->user()->role === 'admin' 
                        ? route('admin.manage-tickets') 
                        : (auth()->user()->role === 'support' 
                            ? route('support.dashboard') 
                            : route('user.dashboard')) }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleFeedback(status) {
    const feedbackSection = document.getElementById('feedbackSection');
    const feedbackInput = document.getElementById('feedback');
    const feedbackRequired = document.getElementById('feedbackRequired');

    if (status === 'ongoing' || status === 'closed') {
        feedbackInput.required = true;
        feedbackRequired.classList.remove('hidden');
        feedbackSection.classList.add('animate-pulse');
        setTimeout(() => feedbackSection.classList.remove('animate-pulse'), 1000);
    } else {
        feedbackInput.required = false;
        feedbackRequired.classList.add('hidden');
    }   
}

document.addEventListener('DOMContentLoaded', function() {
    toggleFeedback(document.getElementById('status').value);
});
</script>
@endpush
@endsection