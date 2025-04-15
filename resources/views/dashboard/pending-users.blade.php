@extends('layouts.admin_app')

@section('content')

@if (session('success'))
                    <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

    <!-- Pending Users Table -->
    <div
        class="bg-white shadow-sm rounded-lg border border-gray-200 mt-6 overflow-hidden hover:shadow-md transition-shadow duration-300">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h2 class="text-lg font-medium text-gray-800 flex items-center">
            <span class="w-1 h-5 bg-gray-600 rounded mr-2"></span>
                Pending Users
            </h2>
            <span class="bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-xs font-medium">
                {{ $pendingUsers->total() }} awaiting approval
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($pendingUsers as $user)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        {{ $user->is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    <span
                                        class="h-1.5 w-1.5 mr-1.5 rounded-full {{ $user->is_approved ? 'bg-green-600' : 'bg-yellow-600' }}"></span>
                                    {{ $user->is_approved ? 'Approve' : 'Pending' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                <form action="{{ route('admin.approve', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded text-xs font-medium transition-colors duration-200 shadow-sm">
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.archive', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-xs font-medium transition-colors duration-200 shadow-sm">
                                        Archive
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    @if(count($pendingUsers) === 0)
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="h-8 w-8 mb-2 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-sm font-medium">No pending users found</p>
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
                Showing {{ $pendingUsers->firstItem() }}–{{ $pendingUsers->lastItem() }} of {{ $pendingUsers->total() }}
                pending user(s)
            </div>
            <div class="text-right">
                <div class="inline-flex items-center space-x-1">
                    {{-- Previous Page Link --}}
                    @if ($pendingUsers->onFirstPage())
                        <span
                            class="px-3 py-1 text-xs text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">Previous</span>
                    @else
                        <a href="{{ $pendingUsers->previousPageUrl() }}"
                            class="px-3 py-1 text-xs text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50">Previous</a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($pendingUsers->getUrlRange(1, $pendingUsers->lastPage()) as $page => $url)
                        @if ($page == $pendingUsers->currentPage())
                            <span
                                class="px-3 py-1 text-xs text-white bg-indigo-600 border border-indigo-600 rounded-md">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="px-3 py-1 text-xs text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($pendingUsers->hasMorePages())
                        <a href="{{ $pendingUsers->nextPageUrl() }}"
                            class="px-3 py-1 text-xs text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50">Next</a>
                    @else
                        <span
                            class="px-3 py-1 text-xs text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">Next</span>
                    @endif
                </div>
            </div>
        </div>
@endsection