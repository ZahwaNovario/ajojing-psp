<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('status', 'menunggu_verifikasi')->latest()->get();
        return view('admin.order.pegawai', compact('orders'));
    }

        public function konfirmasi(Order $order)
    {
        // Pengecekan status sudah benar
        if ($order->status !== 'menunggu_verifikasi') {
            abort(403, 'Pesanan ini tidak dalam status menunggu verifikasi.');
        }

        // [FIX] Tambahkan 'processed_by_user_id' ke dalam array update
        $order->update([
            'status' => 'diproses',
            'processed_by_user_id' => Auth::id() // Ambil ID user (pegawai/admin) yang sedang login
        ]);

        return back()->with('success', "Pesanan #{$order->uuid} telah dikonfirmasi dan siap diproses.");
    }

    public function tolak(Request $request, Order $order)
    {
        if ($order->status !== 'menunggu_verifikasi') {
            abort(403, 'Pesanan tidak bisa diproses.');
        }

        $request->validate(['alasan' => 'required|string']);

        $order->update([
            'status' => 'dibatalkan',
            'alasan_penolakan' => $request->alasan
        ]);

        return back()->with('error', 'Pesanan ditolak.');
    }
}
