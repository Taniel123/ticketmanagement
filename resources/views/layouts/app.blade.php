<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticketing Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Left side navigation -->
                <div class="flex">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-900">Admin Dashboard</a>
                        @elseif(auth()->user()->role === 'support')
                            <a href="{{ route('support.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-900">Support Dashboard</a>
                        @else
                            <a href="{{ route('user.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-900">Dashboard</a>
                        @endif
                    @endauth
                </div>

                <!-- Right side navigation -->
                @auth
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <!-- Role Badge -->
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ auth()->user()->role === 'admin' ? 'bg-purple-100 text-purple-800' : 
                               (auth()->user()->role === 'support' ? 'bg-blue-100 text-blue-800' : 
                               'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                        <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                            class="bg-indigo-500 hover:bg-indigo-600 text-white py-2 px-4 rounded text-sm font-medium transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Main content will be injected here -->
        @yield('content')
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
