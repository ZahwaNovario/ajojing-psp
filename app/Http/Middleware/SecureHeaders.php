<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecureHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jalankan request untuk mendapatkan respons terlebih dahulu
        $response = $next($request);

        // 1. Mencegah Clickjacking (Missing Anti-clickjacking Header)
        // Header ini mencegah halaman Anda ditampilkan di dalam <iframe> di situs lain.
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN', false);

        // 2. Mencegah MIME-sniffing (X-Content-Type-Options Header Missing)
        // Memaksa browser untuk tidak menebak-nebak tipe konten.
        $response->headers->set('X-Content-Type-Options', 'nosniff', false);

        // 3. Menambahkan Content Security Policy (CSP) (CSP Header Not Set)
        // Ini adalah lapisan keamanan terkuat. Memberitahu browser sumber mana saja yang boleh dimuat.
        // Konfigurasi ini adalah contoh dasar yang aman untuk proyek Anda.
        $this->setContentSecurityPolicy($response);

        // 4. (Opsional) Menghapus header yang membocorkan informasi server
        // Ini lebih baik dilakukan di level server (Nginx/Apache), tapi kita bisa coba di sini.
        $response->headers->remove('X-Powered-By');

        return $response;
    }

    private function setContentSecurityPolicy(Response $response)
    {
        // Kita buat aturan yang cukup ketat tapi mengizinkan sumber daya yang kita gunakan.
        $policy = "default-src 'self';"; // Default: hanya izinkan dari domain sendiri.
        $policy .= " script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net;"; // Izinkan script dari domain sendiri & CDN SweetAlert.
        $policy .= " style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net;"; // Izinkan style dari domain sendiri, Google Fonts, & CDN SweetAlert.
        $policy .= " font-src 'self' https://fonts.gstatic.com;"; // Izinkan font dari domain sendiri & Google Fonts.
        $policy .= " img-src 'self' data: https://via.placeholder.com;"; // Izinkan gambar dari domain sendiri, data inline, dan placeholder.
        $policy .= " object-src 'none';"; // Jangan izinkan plugin seperti Flash.
        $policy .= " frame-ancestors 'self';"; // Hanya izinkan iframe dari domain sendiri.
        $policy .= " form-action 'self';"; // Form hanya boleh di-submit ke domain sendiri.
        $policy .= " base-uri 'self';";

        $response->headers->set('Content-Security-Policy', $policy);
    }
}
