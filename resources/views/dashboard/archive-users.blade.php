@extends('layouts.admin_app')

@section('content')
   
   <!-- Archived Users Section -->
   <div class="bg-white shadow-sm rounded-lg border border-gray-200 mt-6 overflow-hidden hover:shadow-md transition-shadow duration-300">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
        <h2 class="text-lg font-medium text-gray-800 flex items-center">
            <span class="w-1 h-5 bg-gray-600 rounded mr-2"></span>
            Archived Users
        </h2>
        <span class="bg-amber-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
            {{ $archivedUsers->total() }} archived users
        </span>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($archivedUsers as $user)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap font-semibold">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
    <span
        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : ($user->role === 'support' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
        <span
            class="h-1.5 w-1.5 mr-1.5 rounded-full 
                {{ $user->role === 'admin' ? 'bg-purple-600' : ($user->role === 'support' ? 'bg-blue-600' : 'bg-gray-600') }}">
        </span>
        {{ ucfirst($user->role) }}
    </span>
</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('users.unarchive', $user->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded text-xs font-medium transition-colors duration-200 shadow-sm">
                                    Unarchive
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No archived users found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-5 bg-gray-50 border-t border-gray-200 text-xs text-gray-600 flex justify-between items-center">
        <div>
            Showing {{ $archivedUsers->firstItem() ?? 0 }}–{{ $archivedUsers->lastItem() ?? 0 }} of {{ $archivedUsers->total() }} archived user(s)
        </div>
        <div class="text-right">
            {{ $archivedUsers->links() }}
        </div>
    </div>
</div>
@endsection