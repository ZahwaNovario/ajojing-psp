@extends('admin.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Log Aktivitas Sistem</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="loginLogTable">
                    <thead class="table-info">
                        <tr>
                            <th>Tanggal</th>
                            <th>Aktivitas</th>
                            <th>Subjek</th>
                            <th>Pelaku</th>
                            <th>Informasi Tambahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activities as $activity)
                            <tr class="table-secondary">
                                <td>
                                    {{-- Waktu Relatif (mudah dibaca) --}}
                                    <span class="fw-bold">{{ $activity->created_at->diffForHumans() }}</span>

                                    {{-- Waktu Absolut (untuk detail) --}}
                                    <p class="text-muted small mb-0">
                                        {{ $activity->created_at->format('d M Y, H:i:s') }}
                                    </p>
                                </td>
                                {{-- Deskripsi Aktivitas --}}
                                <td>{{ $activity->description }}</td>

                                {{-- Tipe Log (Auth, Product, Order, dll) --}}
                                <td>
                                    <span class="badge bg-secondary">{{ $activity->log_name }}</span>
                                </td>

                                {{-- Siapa Pelakunya --}}
                                <td>
                                    @if ($activity->causer)
                                        <i class="ti ti-user-circle me-1"></i> {{ $activity->causer->name }}
                                    @else
                                        {{-- Jika pelaku bukan user terdaftar (misal, saat login gagal) --}}
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>

                                {{-- Menampilkan data tambahan dari kolom `properties` (JSON) --}}
                                <td>
                                    @if ($activity->properties->has('ip_address'))
                                        <p class="mb-0 small">
                                            <i class="ti ti-map-pin me-1"></i>
                                            <strong>IP:</strong> {{ $activity->properties->get('ip_address') }}
                                        </p>
                                    @endif
                                    @if ($activity->properties->has('email'))
                                        <p class="mb-0 small">
                                            <i class="ti ti-mail me-1"></i>
                                            <strong>Email:</strong> {{ $activity->properties->get('email') }}
                                        </p>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada aktivitas yang tercatat.
                                </td>
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
            $('#loginLogTable').DataTable({
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
    </script>
@endpush
