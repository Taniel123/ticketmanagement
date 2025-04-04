@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-sm">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">
                Verify Your Email Address
            </h2>
            <p class="mt-2 text-sm text-gray-500">
                Check your email for a verification link. If you didn't receive an email, you can request another.
            </p>
        </div>

        @if (session('message'))
            <div class="mt-4 rounded-md bg-green-50 p-4">
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

        <form class="mt-8 space-y-6" action="{{ route('verification.resend') }}" method="POST">
            @csrf
            <button type="submit" 
                class="w-full bg-indigo-500 hover:bg-indigo-600 text-white py-2 px-4 rounded text-sm font-medium transition-colors">
                Resend Verification Email
            </button>
        </form>

        <div class="mt-6 text-center">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-sm text-indigo-500 hover:text-indigo-600 font-medium transition-colors">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>
@endsection