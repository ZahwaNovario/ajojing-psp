<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // [PENTING] Baris ini akan memuat routes/web.php (untuk home, login, dll)
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',

        // Menggunakan 'then' untuk mendaftarkan file route kustom kita
        then: function () {
            // Rute untuk Admin & Pegawai
            Route::middleware(['web', 'auth', 'role404:admin,pegawai'])
                // ->prefix('admin')
                // ->name('admin.')
                ->group(base_path('routes/admin.php'));

            // Rute untuk Customer yang sudah Login
            Route::middleware(['web', 'auth'])
                ->group(base_path('routes/customer.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Ini adalah tempat yang benar untuk mendaftarkan middleware alias Anda
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'role404' => \App\Http\Middleware\EnsureMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // Ini adalah cara yang benar untuk meng-handle unauthenticated exception
        $exceptions->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {

            // Cek apakah request yang gagal itu ditujukan untuk halaman admin
            // if ($request->is('admin')) {
            //     // Jika ya, dan pengguna tidak login, jangan redirect, tapi tampilkan 404.
            //     // Ini menjawab permintaan awal Anda untuk menyembunyikan URL admin.
            //     abort(404);
            // }

            // Untuk semua kasus lain (seperti customer mencoba akses /keranjang),
            // biarkan Laravel melakukan perilaku defaultnya: redirect ke halaman login.
            return $request->expectsJson()
                ? response()->json(['message' => $e->getMessage()], 401)
                : redirect()->guest(route('login'));
        });
    })->create();
