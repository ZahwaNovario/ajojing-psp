<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\AccountController;

// Route::get('/dashboard', function () {
//     return view('admin/dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->prefix('keranjang')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/tambah', [CartController::class, 'add'])->name('add');
    Route::patch('/update/{rowId}', [CartController::class, 'update'])->name('update');
    Route::delete('/hapus/{rowId}', [CartController::class, 'remove'])->name('remove');
});

Route::middleware('auth')->prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'process'])->name('process');
    Route::get('/berhasil/{order}', [CheckoutController::class, 'success'])->name('success');
});

Route::middleware('auth')->prefix('akun')->name('account.')->group(function () {
    Route::get('/pesanan', [AccountController::class, 'orders'])->name('orders.index');
    Route::get('/pesanan/{order:uuid}', [AccountController::class, 'orderShow'])->name('orders.show');
    Route::post('/pesanan/{order:uuid}/konfirmasi', [AccountController::class, 'confirmPayment'])->name('orders.confirm-payment');
    // Route::get('/profil', [AccountController::class, 'profile'])->name('profile'); // Contoh untuk nanti
});
