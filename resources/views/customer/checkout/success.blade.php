@extends('layouts.customer')
@section('title', 'Pesanan Berhasil Dibuat')

@section('content')
    <div class="container my-5">
        <div class="text-center py-5">
            <i class="ti ti-circle-check text-success" style="font-size: 5rem;"></i>
            <h2 class="mt-3">Terima Kasih! Pesanan Anda Telah Diterima.</h2>
            <p class="lead text-muted">Nomor pesanan Anda adalah: <strong>#{{ $order->id }}</strong></p>
            <p>Silakan lakukan pembayaran dan konfirmasi agar pesanan Anda dapat segera kami proses.</p>
            <a href="{{ route('home') }}" class="btn btn-primary mt-3">Kembali ke Halaman Utama</a>
            <a href="{{ route('account.orders.index') }}" class="btn btn-outline-secondary mt-3">Lihat Riwayat Pesanan</a>
        </div>
    </div>
@endsection
