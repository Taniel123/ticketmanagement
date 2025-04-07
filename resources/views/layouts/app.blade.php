<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Management System</title>
    @vite('resources/css/app.css')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-white text-gray-800">
    <div class="min-h-screen flex flex-col">
        <!-- Top Navigation Bar -->
        <nav class="bg-white text-gray-800 shadow-md border-b border-gray-200">
            <div class="max-w-8xl mx-2  px-4 py-1 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center ">
               
                        <span class="text-xl font-bold tracking-wide">Ticket Management System</span>
                    </div>
                    <div class="flex items-center space-x-5">
                        <div class="relative">
                            <!-- User Menu -->
                        </div>
                        <div class="relative">
            
                         
                      
                        <form method="POST" action="{{ route('logout') }}">
    @csrf
    <div class="space-x-10">
    <button type="submit" 
        class="w-full text-left px-6 py-2 text-sm font-medium text-white bg-indigo-600 hover:from-blue-600 hover:to-indigo-700 rounded-lg shadow-md transition-all duration-200">
        Sign out
    </button>
    </div>
</form>
                
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex flex-1">
            <!-- Sidebar -->
            <aside class="w-64 bg-white text-gray-800 shadow-xl">
                <div class="h-full flex flex-col">
                    <div class="px-4 py-5 bg-gray-100 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-lg bg-indigo-600 border border-indigo-500 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-semibold">Admin</h3>
                                <div class="flex items-center mt-1">
                                    <span class="bg-green-500 h-2 w-2 rounded-full"></span>
                                    <p class="text-xs text-gray-500 ml-1">Administrator</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <nav class="flex-1 px-3 py-4 space-y-1">
                        <p class="text-xs uppercase tracking-wider text-gray-600 font-semibold px-3 mb-2">Main</p>
                        <a href="#" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg bg-gray-100 text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>
                    </nav>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 overflow-auto focus:outline-none bg-white">
                <div class="py-6 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-2xl font-semibold">Dashboard</h1>
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Dropdown Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const userMenuButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown');

            userMenuButton.addEventListener('click', function () {
                userDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', function (event) {
                if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                    userDropdown.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>
