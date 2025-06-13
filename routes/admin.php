<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\DashboardController;


// Route::middleware(['auth', 'role:admin|pegawai'])->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])
//         ->name('dashboard');
// });

// Route::middleware(['auth'])
//     ->get('/dashboard', [DashboardController::class, 'index'])
//     ->name('dashboard');

Route::middleware(['auth', 'role404:admin,pegawai'])
    ->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

// Route::middleware(['auth', 'role:admin|pegawai'])->group(function () {
//     Route::get('/barang', [DashboardController::class, 'index'])
//         ->name('barang');
// });
