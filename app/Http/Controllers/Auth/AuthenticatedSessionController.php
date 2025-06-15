<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Lakukan proses autentikasi seperti biasa
        $request->authenticate();

        $request->session()->regenerate();

        // 2. Ambil data pengguna yang sudah berhasil login
        $user = Auth::user();

        // 3. Cek rolenya dan tentukan tujuannya
        $url = '';
        if ($user->hasRole('admin')) {
            // Jika admin, tujuan utamanya adalah dashboard admin (misal: halaman manajemen barang)
            $url = route('barang.index');
        } elseif ($user->hasRole('pegawai')) {
            // Jika pegawai, tujuannya adalah halaman manajemen pesanan
            // Sesuai permintaan Anda, ini kita siapkan untuk nanti.
            // Untuk sementara, kita arahkan ke home dulu.
            // $url = route('admin.orders.index');
            $url = route('home');
        } else {
            // Jika bukan keduanya (berarti customer), tujuannya adalah homepage
            $url = route('home');
        }

        // 4. Lakukan redirect ke tujuan yang sudah ditentukan
        return redirect()->intended($url);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
