<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart; // Import facade dari paket shopping cart
use App\Models\Barang; // Import model Barang untuk cek stok

class CartController extends Controller
{
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
                'image' => $request->gambar, // <-- [FIX KECIL] Sesuaikan dengan nama input di form
                'slug'  => $barang->slug
            ]
        ]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }
}
