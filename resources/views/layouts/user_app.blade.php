<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Management System</title>
    @vite('resources/css/app.css')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
    <div class="min-h-screen flex flex-col">

        <nav class="bg-white text-gray-800 shadow-sm border-b border-gray-200 sticky top-0 z-40">
            <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Left Side: Hamburger and Title -->
                    <div class="flex items-center space-x-4">
                        <!-- Hamburger Icon -->
                        <button id="sidebarToggle" class="lg:hidden text-gray-700 focus:outline-none transition-transform transform duration-200 hover:text-indigo-600 p-1 rounded-md hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <!-- Logo & Title -->
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-md bg-indigo-600 flex items-center justify-center mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="text-xl font-semibold text-gray-800">TMS</span>
                            <span class="hidden md:block text-xl font-light text-gray-600 ml-2">| Ticket Management System</span>
                        </div>
                    </div>

                    <!-- Right Side: Sign Out -->
                    <div class="flex items-center space-x-4">
                        <div class="hidden md:flex items-center mr-3 text-sm text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ date('M d, Y') }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md shadow-sm transition-all duration-200 ease-in-out">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H3" />
                                </svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>


        <div class="flex flex-1">
        <aside id="sidebar"
    class="w-64 bg-white text-gray-800 transition-all duration-300 transform lg:translate-x-0 -translate-x-full lg:static fixed z-30 min-h-screen shadow-md flex flex-col">
    <div class="h-full flex flex-col">
        <div class="px-4 py-5 bg-indigo-50 border-b border-gray-200">
            <div class="flex items-center">
                <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold">
                        @if (Auth::user()->role === 'admin')
                            Admin
                        @elseif (Auth::user()->role === 'support')
                            Support
                        @else
                        {{ Auth::user()->name }}
                        @endif
                    </h3>
                    <div class="flex items-center mt-1">
                        <span class="bg-green-500 h-2 w-2 rounded-full"></span>
                        <p class="text-xs text-gray-500 ml-1">
                            @if (Auth::user()->role === 'admin')
                                Administrator
                            @elseif (Auth::user()->role === 'support')
                                Support Staff
                            @else
                                Regular User
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-3 overflow-y-auto">
            <p class="text-xs uppercase tracking-wider text-gray-500 font-semibold px-3 mb-1 py-2">Main Menu</p>
            <a href="{{ route('user.dashboard') }}" 
   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg 
          {{ Route::is('admin.dashboard') ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'hover:bg-indigo-50 text-gray-700 hover:text-indigo-700 hover:border-l-4 hover:border-indigo-600' }}">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 {{ Route::is('admin.dashboard') ? 'text-indigo-600' : 'text-gray-500 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5z" />
    </svg>
    Create tickets
</a>


        </nav>
    </div>
</aside>
            <!-- Main Content -->
            <main class="flex-1 overflow-auto focus:outline-none bg-gray-50">
                <div class="py-6 px-4 sm:px-6 lg:px-8">
                   
                    
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Overlay for mobile sidebar -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-20 hidden lg:hidden"></div>

    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButton = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            toggleButton.addEventListener('click', function () {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            });

            overlay.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        });
    </script>
</body>
</html>