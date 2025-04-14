@extends('layouts.admin')

@section('content')

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-2">
        <!-- Change User Roles Section -->
        <div
            class="bg-white shadow-sm rounded-lg border border-gray-200 mt-6 overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 w-full">
                    <!-- Left Title -->
                    <h1 class="text-xl font-medium text-gray-800 flex items-center">
                        <span class="w-1 h-8 bg-blue-500 rounded mr-2"></span>
                        Manage Users
                    </h1>

                    <!-- Filters and Search -->
                    <form id="filterForm" method="GET" action="{{ route('admin.manage-roles') }}"
                        class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
                        <!-- Role Dropdown -->
                        <select name="role"
                            class="px-3 py-2 border border-gray-300 rounded shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="support" {{ request('role') == 'support' ? 'selected' : '' }}>Support</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                        </select>

                        <!-- Status Dropdown -->
                        <select name="status"
                            class="px-3 py-2 border border-gray-300 rounded shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Statuses</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                        </select>

                        <!-- Search Input -->
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..."
                            class="w-full sm:w-64 px-3 py-2 border border-gray-300 rounded shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </form>
                </div>
            </div>


            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($users as $user)
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">{{ $user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $user->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                                            {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' :
                            ($user->role == 'support' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                                    <span class="h-1.5 w-1.5 mr-1.5 rounded-full
                                                                                                {{ $user->role == 'admin' ? 'bg-purple-600' :
                            ($user->role == 'support' ? 'bg-blue-600' : 'bg-gray-600') }}"></span>
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                                            {{ $user->is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    <span
                                                        class="h-1.5 w-1.5 mr-1.5 rounded-full 
                                                                                                {{ $user->is_approved ? 'bg-green-600' : 'bg-yellow-600' }}"></span>
                                                    {{ $user->is_approved ? 'Active' : 'Pending' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                            <button
    type="button"
    onclick="openRoleModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}')"
    class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded text-xs font-medium transition-colors duration-200 shadow-sm"
>
    Edit
</button>
                                            </td>
                                        </tr>
                        @endforeach

                        @if(count($users) === 0)
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="h-8 w-8 mb-2 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <p class="text-sm font-medium">No users found</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Table Footer/Pagination -->
            <div
                class="px-6 py-5 bg-gray-50 border-t border-gray-200 text-xs text-gray-600 flex justify-between items-center">
                <div>
                    Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }} user(s)
                </div>
                <div class="text-right">
                    <div class="inline-flex items-center space-x-1">
                        {{-- Previous Page Link --}}
                        @if ($users->onFirstPage())
                            <span
                                class="px-3 py-1 text-xs text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">Previous</span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}"
                                class="px-3 py-1 text-xs text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50">Previous</a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                            @if ($page == $users->currentPage())
                                <span
                                    class="px-3 py-1 text-xs text-white bg-indigo-600 border border-indigo-600 rounded-md">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                    class="px-3 py-1 text-xs text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}"
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

<!-- Modal Background -->
<div id="roleModal" class="fixed inset-0 bg-black/30 z-50 hidden items-center justify-center backdrop-blur-xs">
    <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-md">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Update User Role</h2>

        <form id="roleForm" method="POST">
            @csrf
            @method('POST')

            <!-- Name Field -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Name:</label>
                <p id="modalName" class="text-gray-900 mt-1 text-sm font-medium"></p>
            </div>

            <!-- Email Field -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Email:</label>
                <p id="modalEmail" class="text-gray-900 mt-1 text-sm font-medium"></p>
            </div>

            <!-- Role Dropdown -->
            <div class="mb-6">
                <label for="modalRole" class="block text-sm font-medium text-gray-700">Role:</label>
                <select name="role" id="modalRole" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="user">User</option>
                    <option value="support">Support</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end gap-4">
                <button type="button" onclick="closeRoleModal()"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 px-6 py-2 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-gray-400 transition">Cancel</button>
                <button type="submit"
                    class="bg-indigo-600 text-white hover:bg-indigo-700 px-6 py-2 rounded-md text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    Update
                </button>
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

        // Auto-submit on typing in search (on Enter key)
        const searchInput = form.querySelector('input[name="search"]');
        searchInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                form.submit();
            }
        });
    });

    function openRoleModal(userId, name, email, role) {
        document.getElementById('modalName').textContent = name;
        document.getElementById('modalEmail').textContent = email;
        document.getElementById('modalRole').value = role;

        // Update form action
        const form = document.getElementById('roleForm');
        form.action = `/admin/change-role/${userId}`; // adjust this if your route is different

        // Show modal
        document.getElementById('roleModal').classList.remove('hidden');
        document.getElementById('roleModal').classList.add('flex');
    }

    function closeRoleModal() {
        document.getElementById('roleModal').classList.add('hidden');
        document.getElementById('roleModal').classList.remove('flex');
    }
</script>
@endsection