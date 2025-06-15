<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\CartController;

// Route::get('/dashboard', function () {
//     return view('admin/dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('keranjang')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index'); // <-- Aktifkan ini
    Route::post('/tambah', [CartController::class, 'add'])->name('add');
    // ...
});
