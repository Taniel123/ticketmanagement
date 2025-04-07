<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Get the user's current role
        $userRole = $request->user()->role;

        // Check if user's role matches any of the allowed roles
        if (!in_array($userRole, $roles)) {
            // If user is logged in but unauthorized, redirect to their proper dashboard
            return redirect()->route($userRole . '.dashboard')
                ->with('error', 'You are not authorized to access that page.');
        }

        return $next($request);
    }
}