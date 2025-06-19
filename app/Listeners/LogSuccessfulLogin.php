<?php

namespace App\Listeners;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Login $event): void
    {
        /** @var \App\Models\User $user */
        $user = $event->user;
        activity('auth')
            ->causedBy($user)
            ->withProperties(['ip_address' => $this->request->ip()]) // Tambahkan ini
            ->log('Pengguna berhasil login');
    }
}
