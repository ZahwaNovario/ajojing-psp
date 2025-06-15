<?php

use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Customer\ProductController;

// home
Route::get('/', [HomeController::class, 'index'])->name('home');

// auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// produk
Route::get('/produk/{barang:slug}', [ProductController::class, 'show'])->name('produk.show');
Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');

require __DIR__ . '/auth.php';
