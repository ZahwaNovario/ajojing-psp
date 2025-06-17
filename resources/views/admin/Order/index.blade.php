@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h4>Daftar Semua Pesanan</h4>

    <table class="table table-bordered">
        <thead class="table-dark">
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
                    <td>Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted">Belum ada pesanan</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
