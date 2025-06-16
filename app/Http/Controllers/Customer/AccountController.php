<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function orders()
    {
        // Ambil semua order milik user ini, urutkan dari yang terbaru, dan gunakan paginasi
        $orders = Auth::user()->orders()->latest()->paginate(10);

        return view('customer.account.orders', compact('orders'));
    }

    public function orderShow(Order $order)
    {
        // [PENTING] Otorisasi: Pastikan pengguna hanya bisa melihat order miliknya sendiri.
        if (Auth::user()->id !== $order->user_id) {
            abort(404, 'NOT FOUND');
        }

        // Eager load semua relasi yang dibutuhkan agar query efisien
        $order->load('details.barang.images');

        return view('customer.account.order-show', compact('order'));
    }

    public function confirmPayment(Request $request, Order $order)
    {
        // Otorisasi: Pastikan user hanya bisa konfirmasi order miliknya
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Validasi: Pastikan ada file yang diupload dan formatnya benar
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Simpan file bukti pembayaran
        $path = $request->file('bukti_pembayaran')->store('public/bukti-pembayaran');

        // Update record order di database
        $order->update([
            'bukti_pembayaran' => str_replace('public/', '', $path),
            'status' => 'menunggu_verifikasi' // Status berubah, menunggu admin mengecek
        ]);

        return redirect()->route('account.orders.index')->with('success', 'Terima kasih! Bukti pembayaran Anda telah diupload dan akan segera kami verifikasi.');
    }
}
