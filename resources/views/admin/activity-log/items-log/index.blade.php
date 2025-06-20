@extends('admin.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Log Aktivitas Barang</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Waktu</th>
                            <th>Aktivitas</th>
                            <th>Barang Terkait</th>
                            <th>Pelaku</th>
                            <th>Detail Perubahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activities as $activity)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $activity->created_at->diffForHumans() }}</span>
                                    <p class="text-muted small mb-0">{{ $activity->created_at->format('d M Y, H:i:s') }}</p>
                                </td>
                                <td>{{ $activity->description }}</td>
                                <td>
                                    @if ($activity->subject)
                                        <span>{{ $activity->subject->nama }}</span>
                                    @else
                                        <span class="text-muted fst-italic">Data Barang Telah Dihapus</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($activity->causer)
                                        <span><i class="ti ti-user-circle me-1"></i>{{ $activity->causer->name }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($activity->event === 'updated' && $activity->properties->has('old'))
                                        <ul class="list-unstyled mb-0 small">
                                            @foreach ($activity->properties['attributes'] as $key => $value)
                                                @if (isset($activity->properties['old'][$key]) && $activity->properties['old'][$key] != $value)
                                                    <li>
                                                        <strong>{{ ucfirst($key) }}:</strong>
                                                        <span
                                                            class="text-danger">{{ $activity->properties['old'][$key] }}</span>
                                                        &rarr; <span class="text-success">{{ $value }}</span>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada aktivitas pada data barang.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $activities->links() }}
            </div>
        </div>
    </div>
@endsection
