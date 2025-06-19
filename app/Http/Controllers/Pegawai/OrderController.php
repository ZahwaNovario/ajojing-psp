<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('status', 'menunggu_verifikasi')->latest()->get();
        return view('admin.order.pegawai', compact('orders'));
    }

    public function konfirmasi(Order $order)
    {
        if ($order->status !== 'menunggu_verifikasi') {
            abort(403, 'Pesanan tidak bisa diproses.');
        }

        $order->update(['status' => 'diproses']);
        return back()->with('success', 'Pesanan dikonfirmasi.');
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
