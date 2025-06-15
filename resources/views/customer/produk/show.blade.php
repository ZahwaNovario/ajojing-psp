@extends('layouts.customer')
@section('title', $barang->nama) {{-- Judul halaman dinamis --}}

@section('content')
    <div class="container my-5">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row">
                    {{-- Kolom Kiri: Galeri Gambar --}}
                    <div class="col-md-6">
                        {{-- Gambar Utama --}}
                        <div class="mb-3">
                            <img src="{{ $barang->images->isNotEmpty() ? Storage::url($barang->images->first()->path) : 'https://via.placeholder.com/600x400' }}"
                                alt="{{ $barang->nama }}" class="img-fluid rounded w-100" id="main-image"
                                style="height: 400px; object-fit: cover;">
                        </div>
                        {{-- Thumbnail Gambar --}}
                        <div class="d-flex flex-wrap">
                            @foreach ($barang->images as $image)
                                <div class="p-1">
                                    <img src="{{ $image->url }}" alt="Thumbnail {{ $loop->iteration }}"
                                        class="img-thumbnail cursor-pointer thumbnail-image" width="80"
                                        style="height: 80px; object-fit: cover;" data-large-src="{{ $image->url }}">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Kolom Kanan: Info Produk & Aksi --}}
                    <div class="col-md-6">
                        <h1 class="fw-bold">{{ $barang->nama }}</h1>
                        <h3 class="text-danger fw-light mb-3">Rp{{ number_format($barang->harga, 0, ',', '.') }}</h3>

                        <div class="mb-3">
                            <span class="badge {{ $barang->stok > 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ $barang->stok > 0 ? 'Stok Tersedia: ' . $barang->stok : 'Stok Habis' }}
                            </span>
                        </div>

                        <p class="text-muted">{{ $barang->deskripsi }}</p>

                        <hr>

                        {{-- Form Tambah ke Keranjang --}}
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $barang->id }}">
                            <input type="hidden" name="nama" value="{{ $barang->nama }}">
                            <input type="hidden" name="harga" value="{{ $barang->harga }}">
                            {{-- Kita kirim path gambar utama untuk ditampilkan di keranjang --}}
                            <input type="hidden" name="gambar"
                                value="{{ $barang->images->where('is_utama', true)->first()->path ?? $barang->images->first()->path }}">

                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <label for="quantity" class="form-label">Jumlah:</label>
                                    <input type="number" name="quantity" class="form-control" value="1" min="1"
                                        max="{{ $barang->stok }}">
                                </div>
                                <div class="col-md-8">
                                    <button type="submit" class="btn btn-primary btn-lg w-100"
                                        {{ $barang->stok == 0 ? 'disabled' : '' }}>
                                        <i class="ti ti-shopping-cart-plus me-2"></i>
                                        Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Script untuk mengganti gambar utama saat thumbnail di-klik
        document.addEventListener('DOMContentLoaded', function() {
            const mainImage = document.getElementById('main-image');
            const thumbnails = document.querySelectorAll('.thumbnail-image');

            thumbnails.forEach(thumb => {
                thumb.addEventListener('click', function() {
                    mainImage.src = this.getAttribute('data-large-src');
                });
            });
        });
    </script>
@endpush
