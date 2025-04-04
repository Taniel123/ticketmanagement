<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Check if the user is authenticated and has the correct role
        if (Auth::check() && Auth::user()->role == $role) {
            return $next($request);
        }

        // If not authorized, redirect to the home page or login
        return redirect('/home');
    }
}

