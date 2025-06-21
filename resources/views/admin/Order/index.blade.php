@extends('admin.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daftar Semua Pesanan</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="orderTable">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Konfirmasi</th>
                            <th>Total</th>
                            <th>Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ $order->kode }}</td>
                                <td>{{ $order->user->name ?? '-' }}</td>
                                <td>{{ $order->status }}</td>
                                <td>
                                    @if ($order->processor)
                                        {{ $order->processor->name }}
                                    @else
                                        <span class="text-muted fst-italic">Belum Diproses</span>
                                    @endif
                                </td>
                                <td>Rp. {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-detail"
                                        data-url="{{ route('admin.orders.details.json', $order) }}" data-bs-toggle="modal"
                                        data-bs-target="#orderDetailModal">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailModalLabel">Rincian Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="orderDetailModalBody">
                    {{-- Konten akan dimuat oleh AJAX di sini... --}}
                    <p class="text-center">Memuat data...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#orderTable').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/eng.json',
                    emptyTable: "Belum ada data order.",
                    zeroRecords: "Tidak ditemukan hasil pencarian."
                },
                order: [
                    [4, 'desc']
                ]
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const orderDetailModal = document.getElementById('orderDetailModal');

            orderDetailModal.addEventListener('show.bs.modal', function(event) {
                // Tombol yang memicu modal
                const button = event.relatedTarget;
                // Ambil URL API dari atribut data-url
                const dataUrl = button.getAttribute('data-url');

                const modalTitle = orderDetailModal.querySelector('#orderDetailModalLabel');
                const modalBody = orderDetailModal.querySelector('#orderDetailModalBody');

                // Tampilkan status "loading"
                modalTitle.textContent = 'Rincian Pesanan';
                modalBody.innerHTML = '<p class="text-center">Memuat data...</p>';

                // Lakukan fetch request (AJAX)
                fetch(dataUrl)
                    .then(response => response.json())
                    .then(order => {
                        // Update judul modal
                        modalTitle.textContent = `Rincian Pesanan #${order.uuid}`;

                        // Siapkan HTML untuk rincian barang
                        let itemsHtml = '<ul class="list-group list-group-flush">';
                        order.details.forEach(detail => {
                            itemsHtml += `
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    ${detail.kuantitas}x ${detail.nama_barang}
                                    <span>Rp${new Intl.NumberFormat('id-ID').format(detail.harga_saat_pembelian * detail.kuantitas)}</span>
                                </li>
                            `;
                        });
                        itemsHtml += '</ul>';

                        // Bangun seluruh konten body modal
                        modalBody.innerHTML = `
                            <h6><strong>Customer:</strong> ${order.user.name}</h6>
                            <p><strong>Alamat Pengiriman:</strong><br>${order.alamat_pengiriman}</p>
                            <hr>
                            <h6><strong>Rincian Barang:</strong></h6>
                            ${itemsHtml}
                            <hr>
                            <div class="d-flex justify-content-between fs-5 fw-bold">
                                <span>TOTAL</span>
                                <span>Rp${new Intl.NumberFormat('id-ID').format(order.total_harga)}</span>
                            </div>
                        `;
                    })
                    .catch(error => {
                        console.error('Error fetching order details:', error);
                        modalBody.innerHTML =
                            '<p class="text-center text-danger">Gagal memuat data. Silakan coba lagi.</p>';
                    });
            });
        });
    </script>
@endpush
