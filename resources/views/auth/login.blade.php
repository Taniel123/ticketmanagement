@extends('layouts.app') <!-- Extending the app.blade.php layout -->

@section('content')
    <div class="login-form">
        <h2>Login</h2>

        <!-- Display Validation Errors (if any) -->
        @if ($errors->any())
            <div class="error-messages">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" required placeholder="Enter your email" value="{{ old('email') }}">
            </div>

            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" required placeholder="Enter your password">
            </div>

            <div>
                <button type="submit">Login</button>
            </div>
        </form>

        <div>
            <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
        </div>
    </div>
<<<<<<< Updated upstream
=======

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
</a></p>
        </div>
    </div>
>>>>>>> Stashed changes
@endsection
