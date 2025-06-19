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
        $orders = Auth::user()->orders()->latest()->paginate(10);

        return view('customer.account.orders', compact('orders'));
    }

    public function orderShow(Order $order)
    {
        if (Auth::user()->id !== $order->user_id) {
            abort(404, 'NOT FOUND');
        }

        $order->load('details.barang.images');

        return view('customer.account.order-show', compact('order'));
    }
    public function confirmPayment(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $path = null;

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');

            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs("bukti-pembayaran/{$order->id}", $filename, 'public');
        }
        $order->update([
            'bukti_pembayaran' => $path,
            'status' => 'menunggu_verifikasi'
        ]);

        return redirect()->route('account.orders.index')
            ->with('success', 'Terima kasih! Bukti pembayaran Anda telah diupload dan akan segera kami verifikasi.')
            ->with('notifPembayaran', true);
    }
}
