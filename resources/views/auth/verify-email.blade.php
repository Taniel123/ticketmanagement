@extends('layouts.auth')
    
@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white p-8 rounded-xl shadow-md overflow-hidden relative">
        <!-- Decorative accents that match login style -->
        <div class="absolute top-0 left-0 w-1 h-full bg-indigo-600"></div>
        <div class="absolute top-0 right-0 w-1 h-full bg-indigo-600 opacity-30"></div>
        
        <div class="text-center mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <h2 class="mt-4 text-2xl font-semibold text-gray-800">Verify Your Email</h2>
            <p class="mt-2 text-sm text-gray-700">
                We've sent a verification link to your email address.
                <br>Please check your inbox to activate your account.
            </p>
        </div>

        @if (session('message'))
            <div class="rounded-lg bg-green-50 p-4 mb-6 border-l-4 border-green-400">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('message') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="space-y-6">
            <div class="bg-indigo-50 rounded-lg p-4 text-sm text-indigo-700 border border-indigo-100">
                <p>Didn't receive the email? Check your spam folder or request a new verification link.</p>
            </div>
            
            <form action="{{ route('verification.resend') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-800 transition">
                    Resend Verification Email
                </button>
            </form>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-200">
            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit" class="text-sm text-gray-800 hover:text-indigo-600 font-medium transition-colors">
                    Return to Login
                </button>
            </form>
        </div>
    </div>
</div>
@endsection