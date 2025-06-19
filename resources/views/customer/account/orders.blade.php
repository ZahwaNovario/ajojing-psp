@extends('layouts.customer')
@section('title', 'Riwayat Pesanan Saya')

@section('content')
    <div class="container my-5">
        <h2 class="mb-4">Riwayat Pesanan Saya</h2>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                @forelse ($orders as $order)
                    <div class="p-3 mb-3 border rounded">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <h6 class="mb-0">Order #{{ $order->kode }}</h6>
                                <small class="text-muted">{{ $order->created_at->format('d F Y') }}</small>
                            </div>
                            <div class="col-md-3">
                                <span
                                    class="badge
                                @if ($order->status == 'selesai') bg-success
                                @elseif($order->status == 'dikirim') bg-info
                                @elseif($order->status == 'diproses') bg-primary
                                @elseif($order->status == 'dibatalkan') bg-danger
                                @else bg-warning @endif">
                                    {{ str_replace('_', ' ', $order->status) }}
                                </span>
                            </div>
                            <div class="col-md-3">
                                <span class="fw-bold">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="col-md-3 text-end">
                                <a href="{{ route('account.orders.show', $order) }}"
                                    class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                                {{-- Jika status menunggu pembayaran, tampilkan tombol konfirmasi --}}
                                @if ($order->status == 'menunggu_pembayaran')
                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                        data-bs-target="#confirmPaymentModal-{{ $order->id }}">
                                        Konfirmasi Bayar
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="confirmPaymentModal-{{ $order->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('account.orders.confirm-payment', $order) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Konfirmasi Pesanan #{{ $order->kode }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Total Tagihan: <strong
                                                class="text-primary">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</strong>
                                        </p>
                                        <p>Silakan transfer ke rekening BCA <strong>123-456-7890</strong> a/n Ajojing Store.
                                        </p>
                                        <hr>
                                        <div class="mb-3">
                                            <label for="bukti_pembayaran_{{ $order->id }}" class="form-label">Upload
                                                Bukti
                                                Transfer</label>
                                            <input type="file" name="bukti_pembayaran"
                                                id="bukti_pembayaran_{{ $order->id }}"
                                                class="form-control @error('bukti_pembayaran') is-invalid @enderror"
                                                accept="image/png, image/jpeg, image/jpg" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Kirim Konfirmasi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <p class="text-muted">Anda belum memiliki riwayat pesanan.</p>
                        <a href="{{ route('produk.index') }}" class="btn btn-primary">Mulai Belanja</a>
                    </div>
                @endforelse

                {{-- Tombol Paginasi --}}
                <div class="mt-4 d-flex justify-content-center">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            @if ($errors->any())
                let errorMessages = '<ul class="list-unstyled text-start ps-3">';
                errorMessages +=
                    '<li class="mb-1"><i class="ti ti-alert-circle text-danger me-2"></i>Upload gagal! Pastikan file adalah gambar (JPG/PNG) dan ukurannya tidak lebih dari 2MB.</li>';
                errorMessages += '</ul>';

                Swal.fire({
                    icon: 'error',
                    title: 'Oops! Ada yang Salah',
                    html: errorMessages,
                    confirmButtonText: 'Saya Mengerti',
                    confirmButtonColor: '#d33'
                });
            @endif
        </script>
    @endpush
@endsection
