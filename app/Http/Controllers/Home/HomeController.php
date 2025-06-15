<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;

class HomeController extends Controller
{
    public function index()
    {
        // Pindahkan logika query dari file route ke sini
        $barangs = Barang::with('images')->whereHas('images')->latest()->take(8)->get();

        // Kirim data $barangs ke view 'customer.home'
        return view('customer.home', ['barangs' => $barangs]);
    }
}
