@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white p-8 rounded-xl shadow-md">
        <div class="text-center mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <h2 class="mt-4 text-2xl font-semibold text-gray-800">Reset Password</h2>
            <p class="mt-2 text-sm text-gray-700">
                Enter your new password
            </p>
        </div>

        @if ($errors->any())
            <div class="rounded-lg bg-red-50 p-4 mb-6 border-l-4 border-red-400">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form class="space-y-6" action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block text-sm font-medium text-gray-800 mb-1">Email address</label>
                <input id="email" name="email" type="email" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-indigo-200 focus:border-indigo-400 text-gray-800 text-sm"
                    placeholder="Enter your email" value="{{ old('email') }}">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-800 mb-1">New Password</label>
                <input id="password" name="password" type="password" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-indigo-200 focus:border-indigo-400 text-gray-800 text-sm"
                    placeholder="Enter new password">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-800 mb-1">Confirm New Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-indigo-200 focus:border-indigo-400 text-gray-800 text-sm"
                    placeholder="Confirm new password">
            </div>

            <div class="pt-2">
                <button type="submit" 
                    class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-800 transition">
                    Reset Password
                </button>
            </div>
        </form>

        <div class="mt-6 pt-4 border-t border-gray-200">
            <p class="text-center text-sm text-gray-600">
                Remember your password?
                <a href="{{ route('login') }}" class="font-medium text-gray-800 hover:text-gray-900 underline">
                    Back to login
                </a>
            </p>
        </div>
    </div>
</div>
@endsection