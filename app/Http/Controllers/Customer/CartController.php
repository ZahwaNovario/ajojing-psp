<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart; // Pastikan namespace ini benar
use App\Models\Barang;

class CartController extends Controller
{
    /**
     * Menampilkan halaman isi keranjang belanja.
     */
    public function index()
    {
        // Mengambil semua konten dari keranjang
        $cartItems = Cart::content();

        // Memanggil view dan mengirimkan data keranjang
        return view('customer.cart.index', compact('cartItems'));
    }

    /**
     * Menambahkan item baru ke dalam keranjang.
     */
    public function add(Request $request)
    {
        $barang = Barang::findOrFail($request->id);

        $request->validate([
            'id' => 'required|exists:barangs,id',
            'quantity' => "required|integer|min:1|max:{$barang->stok}",
        ]);

        Cart::add([
            'id'      => $barang->id,
            'name'    => $barang->nama,
            'qty'     => $request->quantity,
            'price'   => $barang->harga,
            'weight'  => 0,
            'options' => [
                'image' => $barang->images->where('is_utama', true)->first()->path ?? $barang->images->first()->path,
                'slug'  => $barang->slug
            ]
        ]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Mengubah kuantitas item di dalam keranjang.
     */
    public function update(Request $request, $rowId)
    {
        // Validasi kuantitas baru
        $item = Cart::get($rowId);
        $barang = Barang::find($item->id);
        $request->validate(['quantity' => "required|integer|min:1|max:{$barang->stok}"]);

        Cart::update($rowId, $request->quantity);

        return redirect()->route('cart.index')->with('success', 'Jumlah barang berhasil diupdate.');
    }

    /**
     * Menghapus satu item dari keranjang.
     */
    public function remove($rowId)
    {
        Cart::remove($rowId);

        return redirect()->route('cart.index')->with('success', 'Barang berhasil dihapus dari keranjang.');
    }
}
