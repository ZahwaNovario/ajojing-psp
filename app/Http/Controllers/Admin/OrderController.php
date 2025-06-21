<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->get();
        return view('admin.order.index', compact('orders'));
    }

    public function getDetailsJson(Order $order)
    {
        // Eager load semua relasi yang kita butuhkan untuk ditampilkan di modal
        $order->load(['user', 'processor', 'details.barang']);

        // Kembalikan data order sebagai respons JSON
        return response()->json($order);
    }
}
