@extends('layouts.auth')

@section('content')
<div class="flex flex-col md:flex-row w-screen h-screen overflow-hidden">

    <!-- Left Side (Branding) -->
    <div class="flex-1 flex flex-col justify-center bg-gradient-to-br from-indigo-900 to-purple-700 text-white p-10">
        <p class="ml-0 mt-2 text-6xl md:text-7xl lg:text-8xl font-bold text-white text-left">Create<br>Account</p>

        <div class="mt-12 bg-white/10 backdrop-blur p-6 rounded-xl">
            <h3 class="text-2xl font-semibold mb-4">Join the Ticket System</h3>
            <div class="space-y-4">
                <p class="text-gray-200">Submit tickets, get support, and manage issues efficiently.</p>
                <p class="text-gray-200">Choose your role upon login to access tailored features.</p>
            </div>
        </div>
    </div>

    <!-- Right Side (Register Form) -->
    <div class="flex-1 flex items-center justify-center bg-gray-50 px-6 md:px-16 py-10 relative">
        <div class="w-full max-w-md bg-white p-8 md:p-10 shadow-xl rounded-lg z-10 border border-gray-100">
            <h2 class="text-2xl md:text-3xl font-semibold text-indigo-800 text-center">{{ __('Register') }}</h2>
            <p class="text-center text-gray-600 mt-2">Sign up to get started.</p>

            <form method="POST" action="{{ route('register') }}" class="mt-6">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" type="password" name="password" required
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="mt-6">
                    <button type="submit"
                        class="w-full px-4 py-3 text-white bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150">
                        {{ __('Register') }}
                    </button>
                </div>

                <div class="mt-6 text-center">
                    <p>May account ka na? <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Login here</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection