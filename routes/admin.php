<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\BarangController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Pegawai\OrderController as PegawaiOrderController;


// Akses dashboard: admin dan pegawai
Route::middleware(['auth', 'role404:admin'])
    ->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

// Admin: akses penuh barang
Route::middleware(['auth', 'role404:admin'])->group(function () {
    Route::resource('barang', BarangController::class)
        ->only(['index', 'store', 'update', 'destroy']);
    Route::delete('barang/gambar/{id}/{filename}', [BarangController::class, 'deleteImage'])->name('barang.image.delete');
});

// Pegawai: hanya lihat barang
Route::middleware(['auth', 'role404:pegawai'])->group(function () {
    Route::resource('barang', BarangController::class)
        ->only(['index']);
});

// Admin Order
Route::middleware(['auth', 'role404:admin'])->get('/order', [AdminOrderController::class, 'index'])->name('admin.order.index');

// Pegawai Order
Route::middleware(['auth', 'role404:pegawai'])->group(function () {
    Route::get('/order', [PegawaiOrderController::class, 'index'])->name('pegawai.order.index');
    Route::post('/order/{order}/konfirmasi', [PegawaiOrderController::class, 'konfirmasi'])->name('pegawai.order.konfirmasi');
    Route::post('/order/{order}/tolak', [PegawaiOrderController::class, 'tolak'])->name('pegawai.order.tolak');
});
