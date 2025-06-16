<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart; // Pastikan namespace ini benar
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        // Jika keranjang kosong, redirect kembali ke keranjang
        if (Cart::count() == 0) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong, silakan belanja dulu.');
        }

        $cartItems = Cart::content();
        return view('customer.checkout.index', compact('cartItems'));
    }

    public function process(Request $request)
    {
        // Validasi input form
        $request->validate(['alamat_pengiriman' => 'required|string|min:10']);

        $order = null; // Definisikan di luar transaction agar bisa diakses di bawah

        // Gunakan DB Transaction untuk keamanan data
        DB::transaction(function () use ($request, &$order) {
            // 1. Buat record baru di tabel 'orders'
            // Karena ada trait HasUuids di model Order, kolom 'uuid' akan terisi otomatis.
            $order = Order::create([
                'user_id'           => Auth::id(),
                'total_harga'       => Cart::total(0, '', ''),
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'catatan_pembeli'   => $request->catatan_pembeli,
                'status'            => 'menunggu_pembayaran',
            ]);

            // 2. Pindahkan setiap item dari keranjang ke 'order_details'
            foreach (Cart::content() as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'barang_id' => $item->id,
                    'nama_barang' => $item->name,
                    'kuantitas' => $item->qty,
                    'harga_saat_pembelian' => $item->price,
                ]);

                // 3. Kurangi stok barang
                $barang = Barang::find($item->id);
                if ($barang) {
                    $barang->decrement('stok', $item->qty);
                }
            }

            // 4. Hancurkan (kosongkan) keranjang belanja
            Cart::destroy();
        });

        // Jika order gagal dibuat karena suatu hal
        if (!$order) {
            return redirect()->route('cart.index')->with('error', 'Gagal membuat pesanan, silakan coba lagi.');
        }

        // [FIX] Redirect ke halaman sukses dengan mengirim seluruh objek $order.
        // Laravel cukup pintar untuk otomatis menggunakan 'uuid' dari objek tersebut.
        return redirect()->route('checkout.success', ['order' => $order]);
    }

    public function success(Order $order)
    {
        // Pastikan hanya pemilik order yang bisa melihat halaman ini
        if ($order->user_id !== Auth::id()) {
            abort(404);
        }

        return view('customer.checkout.success', compact('order'));
    }
}
