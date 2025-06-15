<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Barang $barang)
    {
        // Eager load relasi images untuk efisiensi
        $barang->load('images');

        // Panggil view dan kirim data barang
        return view('customer.produk.show', compact('barang'));
    }

    public function index()
    {
        // Ambil semua barang dengan gambar, urutkan dari yang terbaru, dan gunakan paginasi (misal: 12 produk per halaman)
        $barangs = Barang::with('images')->whereHas('images')->latest()->paginate(3);

        return view('customer.produk.index', compact('barangs'));
    }
}
