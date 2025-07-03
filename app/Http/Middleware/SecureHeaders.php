<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecureHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // 1. Mencegah Clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN', false);

        // 2. Mencegah MIME-sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff', false);

        // 3. Tambahkan Content-Security-Policy (CSP)
        if (app()->environment('local')) {
            $this->setRelaxedCspForDev($response); // longgar di lokal
        } else {
            $this->setStrictCsp($response); // ketat di production
        }

        // 4. Hilangkan X-Powered-By header
        $response->headers->remove('X-Powered-By');

        return $response;
    }

    /**
     * CSP Ketat untuk production
     */
    private function setStrictCsp(Response $response): void
    {

        $viteHost = env('VITE_HMR_HOST', 'localhost');
        $vitePort = env('VITE_PORT', '5173');
        $viteUrl = "http://{$viteHost}:{$vitePort}";

        $policy = "default-src 'self';";
        $policy .= " script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net;";
        $policy .= " style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://cdn.jsdelivr.net;";
        $policy .= " font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net;";
        $policy .= " img-src 'self' data: https://via.placeholder.com;";
        $policy .= " object-src 'none';";
        $policy .= " frame-ancestors 'self';";
        $policy .= " form-action 'self';";
        $policy .= " base-uri 'self';";

        $response->headers->set('Content-Security-Policy', $policy);
    }

    /**
     * CSP longgar untuk environment local (vite, dev tools, dll)
     */
    private function setRelaxedCspForDev(Response $response): void
    {
        $policy = "default-src * 'unsafe-inline' 'unsafe-eval' data: blob:;";
        $response->headers->set('Content-Security-Policy', $policy);
    }
}
