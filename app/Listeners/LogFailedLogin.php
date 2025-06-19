<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Http\Request;

class LogFailedLogin
{
    /**
     * @var Request
     */
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Failed $event): void
    {
        // Jika ada percobaan login gagal, catat aktivitasnya
        activity('auth')
            ->withProperties([
                'email' => $event->credentials['email'], // Catat email yang digunakan
                'ip_address' => $this->request->ip()    // Catat alamat IP
            ])
            ->log('Percobaan login gagal');
    }
}
