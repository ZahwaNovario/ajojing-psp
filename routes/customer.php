<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Customer\CartController;
// Route::get('/dashboard', function () {
//     return view('admin/dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->prefix('keranjang')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/tambah', [CartController::class, 'add'])->name('add');
    Route::patch('/update/{rowId}', [CartController::class, 'update'])->name('update');
    Route::delete('/hapus/{rowId}', [CartController::class, 'remove'])->name('remove');
});
