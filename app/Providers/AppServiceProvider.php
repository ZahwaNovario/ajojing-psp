<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

use Illuminate\Cache\RateLimiting\Limit;     // <-- 1. Import Limit
use Illuminate\Support\Facades\RateLimiter; // <-- 2. Import RateLimiter
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        RateLimiter::for('login', function (Request $request) {
            // Batasi 5 kali percobaan per menit
            // Kuncinya berdasarkan email yang diinput DAN alamat IP pengguna
            return Limit::perMinute(5)->by($request->input('email') . $request->ip());
        });
    }
}
