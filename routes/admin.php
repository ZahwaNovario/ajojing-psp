<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\BarangController;



Route::middleware(['auth', 'role404:admin,pegawai'])
    ->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::middleware(['auth', 'role404:admin'])->group(function () {
    Route::resource('barang', BarangController::class)->only(['index', 'store', 'update']);
    Route::delete('barang/gambar/{id}/{filename}', [BarangController::class, 'deleteImage'])->name('barang.image.delete');
});
