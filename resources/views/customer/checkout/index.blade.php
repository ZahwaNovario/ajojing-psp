@extends('layouts.customer')
@section('title', 'Checkout')

@section('content')
    <div class="container my-5">
        <h2 class="mb-4">Checkout</h2>
        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf
            <div class="row">
                {{-- Kolom Kiri: Form Alamat & Pengiriman --}}
                <div class="col-md-7">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Alamat Pengiriman</h5>
                            <div class="mb-3">
                                <label for="alamat_pengiriman" class="form-label">Alamat Lengkap</label>
                                <textarea name="alamat_pengiriman" id="alamat_pengiriman" rows="4"
                                    class="form-control @error('alamat_pengiriman') is-invalid @enderror" required>{{-- Bisa diisi dengan alamat default user jika ada: Auth::user()->alamat --}}</textarea>
                                {{-- BLOK INI AKAN MENAMPILKAN ERROR JIKA ADA --}}
                                @error('alamat_pengiriman')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="catatan_pembeli" class="form-label">Catatan untuk Penjual (Opsional)</label>
                                <textarea name="catatan_pembeli" id="catatan_pembeli" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Ringkasan Pesanan --}}
                <div class="col-md-5 mt-4 mt-md-0">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Ringkasan Pesanan Anda</h5>
                            @foreach ($cartItems as $item)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">{{ $item->name }} (x{{ $item->qty }})</span>
                                    <span>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                            <hr>
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <span>Total Pembayaran</span>
                                <span>Rp {{ Cart::total(0, ',', '.') }}</span>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mt-3 btn-lg">Buat Pesanan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
