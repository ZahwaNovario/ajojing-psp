@extends('layouts.customer')
@section('title', 'Selamat Datang di Ajojing Store')

@section('content')
    {{-- Bagian Hero Banner --}}
    <div class="bg-light">
        <div class="container py-5 text-center">
            <h1 class="display-4 fw-bold">Toko Online Ajojing</h1>
            <p class="fs-5 text-muted">Temukan produk terbaik dengan penawaran menarik hanya untuk Anda.</p>
            <a href="{{ route('produk.index') }}" class="btn btn-primary btn-lg">Belanja Sekarang</a>
        </div>
    </div>

    {{-- Bagian Daftar Produk --}}
    <div class="container mt-5">
        <h2 class="mb-4">Produk Terbaru</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            {{-- Kita akan loop produk di sini nanti --}}
            @if (isset($barangs) && $barangs->count() > 0)
                @foreach ($barangs as $barang)
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            @if ($barang->images->isNotEmpty())
                                <img src="{{ Storage::url($barang->images->first()->path) }}" class="card-img-top"
                                    alt="{{ $barang->nama }}" style="height: 200px; object-fit: cover;">
                            @else
                                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="No image">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $barang->nama }}</h5>
                                <p class="card-text text-danger fw-bold">Rp{{ number_format($barang->harga, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="card-footer bg-white border-0">
                                <a href="{{ route('produk.show', $barang->slug) }}"
                                    class="btn btn-outline-primary w-100">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-muted">Produk akan segera hadir.</p>
            @endif
        </div>
    </div>
@endsection
