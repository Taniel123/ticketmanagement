<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApprovedUser
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()->is_approved) {
            return redirect()->route('verification.notice')
                ->with('error', 'Your account is pending approval.');
        }

        return $next($request);
    }
}