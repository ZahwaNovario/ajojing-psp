@extends('admin.layouts.app')

@section('content')
    <h4 class="mb-3">Pesanan Siap Dikirim</h4>

    

    @forelse ($orders as $order)
        <div class="card mb-4">
            {{-- HEADER KARTU: Info Utama --}}
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Pesanan #{{ $order->uuid }}</h5>
                        <small class="text-muted">Customer: {{ $order->user->name ?? 'N/A' }}</small>
                    </div>
                    <span class="badge bg-light-primary text-primary">{{ str_replace('_', ' ', $order->status) }}</span>
                </div>
            </div>

            {{-- BODY KARTU: Rincian & Form Aksi --}}
            <div class="card-body">
                <div class="row">
                    {{-- Kolom Kiri: Rincian Barang --}}
                    <div class="col-md-7">
                        <h6>Rincian Barang:</h6>
                        <table class="table table-sm">
                            @foreach ($order->details as $detail)
                                <tr>
                                    <td>{{ $detail->kuantitas }}x</td>
                                    <td>{{ $detail->nama_barang }}</td>
                                    <td class="text-end">Rp{{ number_format($detail->harga_saat_pembelian, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <hr>
                        <h6>Alamat Pengiriman:</h6>
                        <p class="text-muted">{{ $order->alamat_pengiriman }}</p>
                    </div>

                    {{-- Kolom Kanan: Form Pengiriman --}}
                    <div class="col-md-5 border-start">
                        <h6>Aksi Pengiriman</h6>
                        <p class="small text-muted">Input nomor resi untuk mengubah status menjadi "Dikirim".</p>
                        <form action="{{ route('pegawai.orders.ship', $order) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="nomor_resi_{{ $order->id }}" class="form-label">Nomor Resi</label>
                                <input type="text" name="nomor_resi" id="nomor_resi_{{ $order->id }}"
                                    class="form-control" required placeholder="Masukkan nomor resi...">
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-info">
                                    <i class="ti ti-truck-delivery me-2"></i>Kirim Pesanan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <i class="ti ti-package text-muted" style="font-size: 4rem;"></i>
            <h4 class="mt-3">Tidak Ada Pesanan untuk Diproses</h4>
            <p class="text-muted">Semua pesanan sudah terkirim atau menunggu verifikasi.</p>
        </div>
    @endforelse

    {{-- Paginasi jika Anda menggunakannya --}}
    {{-- <div class="mt-4">{{ $orders->links() }}</div> --}}
@endsection
