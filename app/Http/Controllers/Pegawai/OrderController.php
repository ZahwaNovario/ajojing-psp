<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('status', 'menunggu_verifikasi')->latest()->get();
        return view('admin.order.pegawai', compact('orders'));
    }

    public function daftarPesanan()
    {
        $orders = Order::with([
            'user',
            'details.barang'
        ])
            ->where('status', 'diproses')
            ->latest()
            ->paginate(15);

        return view('admin.order.pegawai_orderlist', compact('orders'));
    }
    public function konfirmasi(Order $order)
    {
        if ($order->status !== 'menunggu_verifikasi') {
            abort(403, 'Pesanan ini tidak dalam status menunggu verifikasi.');
        }

        $order->update([
            'status' => 'diproses',
            'processed_by_user_id' => Auth::id()
        ]);

        return back()->with('success', "Pesanan #{$order->uuid} telah dikonfirmasi dan siap diproses.");
    }

    public function tolak(Request $request, Order $order)
    {
        if ($order->status !== 'menunggu_verifikasi') {
            abort(403, 'Pesanan tidak bisa diproses.');
        }

        $request->validate(['alasan' => 'required|string']);

        DB::transaction(function () use ($order, $request) {
            foreach ($order->details as $detail) {
                Barang::find($detail->barang_id)->increment('stok', $detail->kuantitas);
            }

            $order->update([
                'status' => 'dibatalkan',
                'alasan_penolakan' => $request->alasan
            ]);
        });
        return back()->with('error', 'Pesanan ditolak.');
    }
    public function ship(Request $request, Order $order)
    {
        if ($order->status !== 'diproses') {
            return back()->with('error', 'Pesanan ini tidak bisa dikirim.');
        }

        $request->validate(['nomor_resi' => 'required|string|max:255']);

        $order->update([
            'status' => 'dikirim',
            'nomor_resi' => $request->nomor_resi,
            'processed_by_user_id' => Auth::id() // Catat siapa yang mengirim
        ]);

        activity('Order')
            ->performedOn($order)
            ->causedBy(Auth::user())
            ->log("Pesanan dikirim dengan nomor resi: {$request->nomor_resi}");

        return back()->with('success', "Pesanan #{$order->uuid} telah ditandai sebagai 'Dikirim'.");
    }
}
