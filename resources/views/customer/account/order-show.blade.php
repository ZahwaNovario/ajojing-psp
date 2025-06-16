@extends('layouts.customer')
@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Detail Pesanan <span class="text-primary">#{{ $order->id }}</span></h2>
            <a href="{{ route('account.orders.index') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left"></i> Kembali ke Riwayat
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                {{-- Rincian Produk --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Produk yang Dipesan</h5>
                    </div>
                    <div class="card-body">
                        @foreach ($order->details as $detail)
                            <div class="row align-items-center mb-3">
                                <div class="col-2">
                                    <img src="{{ $detail->barang->images->first() ? Storage::url($detail->barang->images->first()->path) : 'https://via.placeholder.com/150' }}"
                                        alt="{{ $detail->nama_barang }}" class="img-fluid rounded">
                                </div>
                                <div class="col-5">
                                    <h6 class="mb-0">{{ $detail->nama_barang }}</h6>
                                    <small class="text-muted">{{ $detail->kuantitas }} x
                                        Rp{{ number_format($detail->harga_saat_pembelian, 0, ',', '.') }}</small>
                                </div>
                                <div class="col-5 text-end">
                                    <p class="mb-0 fw-bold">
                                        Rp{{ number_format($detail->kuantitas * $detail->harga_saat_pembelian, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                            @if (!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mt-4 mt-lg-0">
                {{-- Detail Pengiriman & Total --}}
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="mb-3">Ringkasan</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Status</span>
                                <span class="badge bg-warning">{{ str_replace('_', ' ', $order->status) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between fw-bold fs-5">
                                <span>Total</span>
                                <span>Rp{{ number_format($order->total_harga, 0, ',', '.') }}</span>
                            </li>
                        </ul>
                        <hr>
                        <h6>Alamat Pengiriman</h6>
                        <p class="text-muted">{{ $order->alamat_pengiriman }}</p>
                        <hr>
                        @if ($order->status == 'menunggu_pembayaran')
                            <button type="button" class="btn btn-success w-100" data-bs-toggle="modal"
                                data-bs-target="#confirmPaymentModal-{{ $order->id }}">
                                Konfirmasi Pembayaran
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal konfirmasi pembayaran --}}
    <div class="modal fade" id="confirmPaymentModal-{{ $order->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('account.orders.confirm-payment', $order) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Pesanan #{{ $order->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Total Tagihan: <strong
                                class="text-primary">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</strong></p>
                        <p>Silakan transfer ke rekening BCA <strong>123-456-7890</strong> a/n Ajojing Store.</p>
                        <hr>
                        <div class="mb-3">
                            <label for="bukti_pembayaran" class="form-label">Upload Bukti Transfer</label>
                            <input type="file" name="bukti_pembayaran" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Kirim Konfirmasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
