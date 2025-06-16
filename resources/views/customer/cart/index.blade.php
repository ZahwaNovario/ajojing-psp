@extends('layouts.customer')
@section('title', 'Keranjang Belanja')

@section('content')
    <div class="container my-5">
        <h2 class="mb-4">Keranjang Belanja Anda</h2>

        {{-- Tampilkan notifikasi jika ada --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Cek apakah keranjang ada isinya --}}
        @if (Cart::count() > 0)
            <div class="row">
                {{-- Kolom Kiri: Daftar Item --}}
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            @foreach ($cartItems as $item)
                                <div class="row mb-4 align-items-center">
                                    <div class="col-md-2 col-3">
                                        <img src="{{ Storage::url($item->options->image) }}" alt="{{ $item->name }}"
                                            class="img-fluid rounded">
                                    </div>
                                    <div class="col-md-3 col-9">
                                        <a href="{{ route('produk.show', $item->options->slug) }}"
                                            class="text-dark fw-bold text-decoration-none">{{ $item->name }}</a>
                                        <p class="text-muted small mb-0">Rp{{ number_format($item->price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="col-md-3 col-6 mt-3 mt-md-0">
                                        {{-- Form Update Kuantitas --}}
                                        <form action="{{ route('cart.update', $item->rowId) }}" method="POST"
                                            class="d-flex">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $item->qty }}"
                                                class="form-control form-control-sm" style="width: 70px;">
                                            <button type="submit"
                                                class="btn btn-sm btn-outline-primary ms-2">Update</button>
                                        </form>
                                    </div>
                                    <div class="col-md-3 col-4 mt-3 mt-md-0 text-md-end">
                                        <strong>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                                    </div>
                                    <div class="col-md-1 col-2 mt-3 mt-md-0 text-end">
                                        {{-- Form Hapus Item --}}
                                        <form action="{{ route('cart.remove', $item->rowId) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                title="Hapus Item">&times;</button>
                                        </form>
                                    </div>
                                </div>
                                @if (!$loop->last)
                                    <hr>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Ringkasan Belanja --}}
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Ringkasan Belanja</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Subtotal</span>
                                    <span>Rp {{ Cart::subtotal(0, ',', '.') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Pajak (0%)</span>
                                    <span>Rp {{ Cart::tax(0, ',', '.') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between fw-bold fs-5">
                                    <span>Total</span>
                                    <span>Rp {{ Cart::total(0, ',', '.') }}</span>
                                </li>
                            </ul>
                            <a href="#" class="btn btn-primary w-100 mt-3">Lanjut ke Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="ti ti-shopping-cart text-muted" style="font-size: 5rem;"></i>
                <h4 class="mt-3">Keranjang Anda masih kosong</h4>
                <p class="text-muted">Ayo mulai belanja dan temukan produk favoritmu!</p>
                <a href="{{ route('produk.index') }}" class="btn btn-primary mt-2">Mulai Belanja</a>
            </div>
        @endif
    </div>
@endsection
