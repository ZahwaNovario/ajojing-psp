<?php

use App\Http\Controllers\Admin\ActivityLogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\BarangController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Pegawai\OrderController as PegawaiOrderController;


// Akses dashboard: admin dan pegawai
Route::middleware(['auth', 'role404:admin'])
    ->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

// Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role404:admin'])->group(function () {
    Route::resource('barang', BarangController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::delete('barang/gambar/{id}/{filename}', [BarangController::class, 'deleteImage'])->name('barang.image.delete');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('order.index');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/login-log', [ActivityLogController::class, 'index'])->name('activity-log.login-log.index');
});

// Pegawai
Route::prefix('pegawai')->name('pegawai.')->middleware(['auth', 'role404:pegawai'])->group(function () {
    Route::resource('barang', BarangController::class)->only(['index']);
    Route::get('/order', [PegawaiOrderController::class, 'index'])->name('order.index');
    Route::post('/order/{order}/konfirmasi', [PegawaiOrderController::class, 'konfirmasi'])->name('order.konfirmasi');
    Route::post('/order/{order}/tolak', [PegawaiOrderController::class, 'tolak'])->name('order.tolak');
});
