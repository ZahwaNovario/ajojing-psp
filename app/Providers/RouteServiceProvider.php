<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public const HOME = '/'; // Arahkan ke homepage customer setelah login

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        // Route::middleware(['web', 'auth', 'role404:admin,pegawai'])
        //     ->group(base_path('routes/admin.php'));

        // Route::middleware(['web', 'auth'])
        //     ->group(base_path('routes/customer.php'));

        // Route::middleware('web')
        //     ->group(base_path('routes/web.php'));
    }
}
