@extends('layouts.auth')

@section('content')
<div class="flex flex-col md:flex-row w-screen h-screen overflow-hidden">

    <!-- Left Side (Professional Branding) -->
    <div class="flex-1 flex flex-col justify-center bg-gradient-to-br from-indigo-900 to-purple-700 text-white p-10">
        <p class="ml-0 mt-2 text-6xl md:text-7xl lg:text-8xl font-bold text-white text-left">Welcome <br>Back!</p>

        <div class="mt-12 bg-white/10 backdrop-blur p-6 rounded-xl">
            <h3 class="text-2xl font-semibold mb-4">Ticket Management System</h3>
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="p-3 rounded-full bg-green-500 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold">User</h4>
                        <p class="text-sm text-gray-200">Submit and track support tickets</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="p-3 rounded-full bg-yellow-500 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold">Support</h4>
                        <p class="text-sm text-gray-200">Resolve tickets and communicate with users</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="p-3 rounded-full bg-red-500 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold">Admin</h4>
                        <p class="text-sm text-gray-200">Manage users, tickets, and system settings</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side (Login Form) -->
    <div class="flex-1 flex items-center justify-center bg-gray-50 px-6 md:px-16 py-10 relative">
        <div class="w-full max-w-md bg-white p-8 md:p-10 shadow-xl rounded-lg z-10 border border-gray-100">
            <h2 class="text-2xl md:text-3xl font-semibold text-indigo-800 text-center">{{ __('Login') }}</h2>
            <p class="text-center text-gray-600 mt-2">Access your account securely.</p>

            <form method="POST" action="{{ route('login') }}" class="mt-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Username') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus 
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" 
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

    

                <div class="mt-4 flex items-center">
                    <input class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember" class="ml-2 text-sm text-gray-600">{{ __('Remember Me') }}</label>

                    @if (Route::has('password.request'))
                        <a class="ml-auto text-sm text-indigo-600 hover:text-indigo-500" href="{{ route('password.request') }}">
                            {{ __('Forgot Password?') }}
                        </a>
                    @endif
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full px-4 py-3 text-white bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150">
                        {{ __('Sign In') }}
                    </button>
                </div>

                <div class="mt-6">
            <p>Wala ka pa bang account ngani? <a href="{{ route('register') }}">Register here</a></p>
        </div>
            </form>
        </div>
    </div>
</div>
@endsection