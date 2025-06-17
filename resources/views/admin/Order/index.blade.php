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
                            <th>Total</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ $order->kode }}</td>
                                <td>{{ $order->user->name ?? '-' }}</td>
                                <td>{{ $order->status }}</td>
                                <td>Rp. {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada pesanan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/eng.json'
                },
                order: [
                    [4, 'desc']
                ]
            });
        });
    </script>
@endpush
