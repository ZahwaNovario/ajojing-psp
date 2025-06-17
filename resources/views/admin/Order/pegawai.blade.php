@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h4>Konfirmasi Pesanan</h4>

        <div class="row">
            @forelse ($orders as $order)
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5>Kode: {{ $order->kode }}</h5>
                            <p><strong>Customer:</strong> {{ $order->user->name ?? '-' }}</p>
                            <p><strong>Total:</strong> Rp{{ number_format($order->total, 0, ',', '.') }}</p>
                            <p><strong>Waktu:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>

                            <form action="{{ route('pegawai.orders.konfirmasi', $order) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-success btn-sm">Konfirmasi</button>
                            </form>

                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#tolakModal{{ $order->id }}">Tolak</button>

                            <!-- Modal Tolak -->
                            <div class="modal fade" id="tolakModal{{ $order->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('pegawai.orders.tolak', $order) }}" method="POST"
                                        class="modal-content">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tolak Pesanan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label>Alasan Penolakan</label>
                                            <textarea name="alasan" class="form-control" required></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-danger">Kirim</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">Tidak ada pesanan yang menunggu verifikasi.</p>
            @endforelse
        </div>
    </div>
@endsection
