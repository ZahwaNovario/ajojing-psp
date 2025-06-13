@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h4 class="mb-3">Daftar Barang</h4>

        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createBarangModal">
            Tambah Barang
        </button>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barangs as $barang)
                    <tr>
                        <td>{{ $barang->nama }}</td>
                        <td>{{ $barang->deskripsi }}</td>
                        <td>{{ $barang->stok }}</td>
                        <td>Rp{{ number_format($barang->harga, 0, ',', '.') }}</td>
                        <td>
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#showImagesModal{{ $barang->id }}">Lihat Gambar</button>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                data-bs-target="#editModal{{ $barang->id }}">Edit</button>
                        </td>
                    </tr>

                    <!-- Show Images Modal -->
                    <div class="modal fade" id="showImagesModal{{ $barang->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Gambar Barang: {{ $barang->nama }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">

                                    @php
                                        $folder = storage_path("app/public/barang/{$barang->id}");
                                        $files = File::exists($folder) ? File::files($folder) : [];
                                    @endphp

                                    @if (count($files))
                                        <div id="carousel-{{ $barang->id }}" class="carousel slide"
                                            data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                @foreach ($files as $i => $file)
                                                    @php $filename = $file->getFilename(); @endphp
                                                    <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                                                        <img src="{{ Storage::url('barang/' . $barang->id . '/' . $filename) }}"
                                                            class="d-block w-100 img-fluid rounded"
                                                            style="max-height: 400px;">
                                                        <div class="text-center mt-2">
                                                            <form
                                                                action="{{ route('barang.image.delete', [$barang->id, $filename]) }}"
                                                                method="POST">
                                                                @csrf @method('DELETE')
                                                                <button class="btn btn-sm btn-danger">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button class="carousel-control-prev" type="button"
                                                data-bs-target="#carousel-{{ $barang->id }}" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon"></span>
                                            </button>
                                            <button class="carousel-control-next" type="button"
                                                data-bs-target="#carousel-{{ $barang->id }}" data-bs-slide="next">
                                                <span class="carousel-control-next-icon"></span>
                                            </button>
                                        </div>
                                    @else
                                        <p class="text-muted">Belum ada gambar.</p>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Create Barang Modal -->
                    <div class="modal fade" id="createBarangModal" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tambah Barang</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label>Nama</label>
                                            <input type="text" name="nama" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Deskripsi</label>
                                            <textarea name="deskripsi" class="form-control" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label>Stok</label>
                                            <input type="number" name="stok" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Harga</label>
                                            <input type="number" name="harga" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Tambah Gambar</label>
                                            <div id="gambarInputsEdit{{ $barang->id }}">
                                                <div class="input-group mb-2">
                                                    <input type="file" name="gambar[]" class="form-control">
                                                    <button type="button" class="btn btn-outline-danger"
                                                        onclick="this.parentElement.remove()">✖</button>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-secondary"
                                                onclick="addInputWithRemove('gambarInputsEdit{{ $barang->id }}')">+
                                                Tambah Gambar</button>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Barang Modal -->
                    <div class="modal fade" id="editModal{{ $barang->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form action="{{ route('barang.update', $barang->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Barang</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label>Nama</label>
                                            <input type="text" name="nama" value="{{ $barang->nama }}"
                                                class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Deskripsi</label>
                                            <textarea name="deskripsi" class="form-control" required>{{ $barang->deskripsi }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label>Stok</label>
                                            <input type="number" name="stok" value="{{ $barang->stok }}"
                                                class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Harga</label>
                                            <input type="number" name="harga" value="{{ $barang->harga }}"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function addInputWithRemove(targetId) {
            const container = document.getElementById(targetId);
            const wrapper = document.createElement('div');
            wrapper.classList.add('input-group', 'mb-2');

            const input = document.createElement('input');
            input.type = 'file';
            input.name = 'gambar[]';
            input.classList.add('form-control');

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.classList.add('btn', 'btn-outline-danger');
            btn.innerText = '✖';
            btn.onclick = () => {
                if (container.childElementCount > 1) {
                    wrapper.remove();
                } else {
                    alert('Minimal satu input gambar harus ada.');
                }
            };

            wrapper.appendChild(input);
            wrapper.appendChild(btn);
            container.appendChild(wrapper);
        }
    </script>
@endsection
