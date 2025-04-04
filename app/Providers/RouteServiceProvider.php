<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // ...existing code...

        $this->app['router']->aliasMiddleware('role', \App\Http\Middleware\CheckRole::class);
        
        // ...existing code...
    }
}
