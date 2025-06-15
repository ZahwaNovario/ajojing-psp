@extends('layouts.customer')
@section('title', 'Semua Produk')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-3">
            {{-- Kolom untuk filter nanti --}}
            <h4>Filter</h4>
            <hr>
            <p>Filter berdasarkan kategori dan harga akan kita buat di sini nanti.</p>
        </div>
        <div class="col-md-9">
            <h2 class="mb-4">Semua Produk</h2>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @forelse ($barangs as $barang)
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0">
                            {{-- Salin-tempel dari home.blade.php agar konsisten --}}
                            @if($barang->images->isNotEmpty())
                                <img src="{{ Storage::url($barang->images->first()->path) }}" class="card-img-top" alt="{{ $barang->nama }}" style="height: 200px; object-fit: cover;">
                            @else
                                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="No image">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $barang->nama }}</h5>
                                <p class="card-text text-danger fw-bold">Rp{{ number_format($barang->harga, 0, ',', '.') }}</p>
                            </div>
                            <div class="card-footer bg-white border-0">
                                <a href="{{ route('produk.show', $barang->slug) }}" class="btn btn-outline-primary w-100">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Tidak ada produk yang ditemukan.</p>
                @endforelse
            </div>

            {{-- TOMBOL PAGINASI --}}
            <div class="mt-5 d-flex justify-content-center">
                {{ $barangs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
