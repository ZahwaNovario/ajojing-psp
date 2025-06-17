@extends('admin.layouts.app')

@section('content')
    {{-- Card Wrapper untuk tampilan yang lebih rapi --}}
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Manajemen Barang</h5>
                @role('admin')
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createBarangModal">
                        <i class="ti ti-plus me-2"></i>Tambah Barang Baru
                    </button>
                @endrole
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="res-config" class="display table table-striped table-hover dt-responsive nowrap"
                    style="width: 100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Gambar</th>
                            <th>Nama</th>
                            <th>Stok</th>
                            <th>Harga</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barangs as $barang)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{-- Menampilkan thumbnail dari relasi 'images' --}}
                                    @if ($barang->images->isNotEmpty())
                                        <img src="{{ Storage::url($barang->images->where('is_utama', true)->first()->path ?? $barang->images->first()->path) }}"
                                            alt="{{ $barang->nama }}" width="120" class="img-thumbnail">
                                    @else
                                        {{-- Placeholder jika tidak ada gambar --}}
                                        <img src="https://via.placeholder.com/150" alt="No Image" width="60"
                                            class="img-thumbnail">
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $barang->nama }}</strong>
                                    {{-- Menampilkan deskripsi singkat --}}
                                    <p class="text-muted small mb-0">{{ Str::limit($barang->deskripsi, 50) }}</p>
                                </td>
                                <td>{{ $barang->stok }}</td>
                                <td>Rp{{ number_format($barang->harga, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    {{-- Tombol Aksi dengan Ikon dari tema Anda --}}
                                    <button class="btn btn-sm btn-info" title="Lihat Gambar" data-bs-toggle="modal"
                                        data-bs-target="#showImagesModal" data-nama-barang="{{ $barang->nama }}"
                                        data-images="{{ json_encode($barang->images->pluck('path')->map(fn($path) => Storage::url($path))) }}">
                                        <i class="ti ti-photo"></i>
                                    </button>
                                    @role('admin')
                                        <button class="btn btn-sm btn-warning" title="Edit Barang" data-bs-toggle="modal"
                                            data-bs-target="#editBarangModal" {{-- Kita kirim data barang LENGKAP DENGAN GAMBARNYA --}}
                                            data-barang="{{ json_encode($barang->load('images')) }}"
                                            data-action="{{ route('admin.barang.update', $barang->id) }}">
                                            <i class="ti ti-pencil"></i>
                                        </button>

                                        <form action="{{ route('admin.barang.destroy', $barang->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger btn-delete"
                                                title="Hapus Barang">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    @endrole
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada data barang. Silakan
                                    tambahkan barang baru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- MODALS (Hanya ada SATU untuk setiap jenis, di luar loop) --}}
    {{-- ========================================================================= --}}

    <div class="modal fade" id="showImagesModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showImagesModalTitle">Gambar Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="showImagesModalBody">
                    {{-- Carousel akan dibuat oleh JavaScript di sini --}}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editBarangModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editBarangForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Input-input lama (nama, deskripsi, stok, harga) biarkan seperti semula --}}
                        <input type="hidden" name="id" id="editId">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" id="editNama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="editDeskripsi" class="form-control" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Stok</label>
                                <input type="number" name="stok" id="editStok" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga</label>
                                <input type="number" name="harga" id="editHarga" class="form-control" required>
                            </div>
                        </div>

                        <hr>

                        {{-- BAGIAN BARU UNTUK MENAMPILKAN GAMBAR LAMA --}}
                        <div class="mb-3">
                            <label class="form-label">Gambar yang Sudah Ada</label>
                            <div id="existingImagesContainer" class="row g-2">
                                {{-- Gambar lama akan dimuat oleh JavaScript di sini --}}
                                {{-- Contoh satu item: --}}
                                {{-- <div class="col-auto">
                <div class="position-relative">
                    <img src="..." class="img-thumbnail" width="100">
                    <button class="btn btn-sm btn-danger position-absolute top-0 end-0">&times;</button>
                </div>
            </div> --}}
                            </div>
                        </div>

                        {{-- BAGIAN BARU UNTUK MENAMBAH GAMBAR BARU --}}
                        <div class="mb-3">
                            <label class="form-label">Tambah Gambar Baru</label>
                            <div id="newImageInputsContainer">
                                {{-- Input untuk gambar baru akan ditambah oleh JavaScript di sini --}}
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary mt-2"
                                onclick="addNewImageInput('newImageInputsContainer')">
                                <i class="ti ti-plus"></i> Tambah Input
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Modal Tambah Barang (Bisa Anda include dari file lain agar lebih rapi) --}}
    @include('admin.barang.create_barang')


    @push('scripts')
        <script>
            /**
             * @deskripsi Fungsi ini untuk menambah input file baru secara dinamis.
             * @param {string} containerId - ID dari div yang akan menampung input baru.
             */
            function addNewImageInput(containerId) {
                const container = document.getElementById(containerId);
                const wrapper = document.createElement('div');
                wrapper.classList.add('input-group', 'mb-2');
                wrapper.innerHTML = `
            <input type="file" name="gambar[]" class="form-control" accept="image/*">
            <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                <i class="ti ti-trash"></i>
            </button>
        `;
                container.appendChild(wrapper);
            }

            /**
             * Menjalankan semua script setelah seluruh struktur halaman (DOM) selesai dimuat.
             * Ini adalah praktik terbaik untuk menghindari error 'element not found'.
             */
            document.addEventListener('DOMContentLoaded', function() {
                // --- Script untuk Modal Lihat Gambar Dinamis ---
                const imagesModalEl = document.getElementById('showImagesModal');
                if (imagesModalEl) {
                    imagesModalEl.addEventListener('show.bs.modal', function(event) {
                        const button = event.relatedTarget;
                        const namaBarang = button.getAttribute('data-nama-barang');
                        const images = JSON.parse(button.getAttribute('data-images'));
                        const modalTitle = imagesModalEl.querySelector('#showImagesModalTitle');
                        const modalBody = imagesModalEl.querySelector('#showImagesModalBody');

                        modalTitle.textContent = 'Gambar Barang: ' + namaBarang;
                        modalBody.innerHTML = ''; // Kosongkan isi modal sebelum diisi ulang

                        if (images && images.length > 0) {
                            let carouselItemsHTML = images.map((url, index) => `
                            <div class="carousel-item ${index === 0 ? 'active' : ''}">
                                <img src="${url}" class="d-block w-100 img-fluid rounded" style="max-height: 450px; object-fit: contain;" alt="${namaBarang} - Gambar ${index + 1}">
                            </div>
                        `).join('');

                            const carouselHTML = `
                            <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">${carouselItemsHTML}</div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        `;
                            modalBody.innerHTML = carouselHTML;
                        } else {
                            modalBody.innerHTML =
                                '<p class="text-muted text-center py-5">Tidak ada gambar untuk barang ini.</p>';
                        }
                    });
                }
                const editModalEl = document.getElementById('editBarangModal');
                // 'Flag' untuk menandai apakah ada perubahan (penghapusan gambar) di dalam modal.
                let imagesHaveChanged = false;

                // Cek apakah elemen modal edit ada di halaman ini.
                if (editModalEl) {

                    // Menjalankan kode setiap kali modal edit AKAN DITAMPILKAN.
                    editModalEl.addEventListener('show.bs.modal', function(event) {
                        // Reset flag ke false setiap kali modal dibuka.
                        imagesHaveChanged = false;

                        // Mengambil data dari tombol yang di-klik.
                        const button = event.relatedTarget;
                        const barang = JSON.parse(button.getAttribute('data-barang'));
                        const action = button.getAttribute('data-action');

                        // Menemukan elemen-elemen form di dalam modal.
                        const form = editModalEl.querySelector('#editBarangForm');
                        const existingImagesContainer = editModalEl.querySelector('#existingImagesContainer');
                        const newImageInputsContainer = editModalEl.querySelector('#newImageInputsContainer');

                        // Mengisi form dengan data barang yang akan diedit.
                        form.action = action;
                        form.querySelector('#editId').value = barang.id;
                        form.querySelector('#editNama').value = barang.nama;
                        form.querySelector('#editDeskripsi').value = barang.deskripsi;
                        form.querySelector('#editStok').value = barang.stok;
                        form.querySelector('#editHarga').value = barang.harga;

                        // Kosongkan kontainer gambar dari data sebelumnya.
                        existingImagesContainer.innerHTML = '';
                        newImageInputsContainer.innerHTML = '';

                        // Buat dan tampilkan daftar gambar yang sudah ada.
                        if (barang.images && barang.images.length > 0) {
                            barang.images.forEach(image => {
                                const filename = image.path.split('/').pop();
                                const imgWrapper = document.createElement('div');
                                imgWrapper.classList.add('col-auto',
                                    'image-wrapper'); // Beri kelas untuk dihitung
                                imgWrapper.setAttribute('id', `image-wrapper-${image.id}`);
                                imgWrapper.innerHTML = `
                            <div class="position-relative">
                                <img src="/storage/${image.path}" class="img-thumbnail" width="100" height="100" style="object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger rounded-circle position-absolute top-0 end-0 mt-n1 me-n1 btn-delete-existing-image" data-image-id="${image.id}" data-barang-id="${barang.id}" data-filename="${filename}" title="Hapus Gambar Ini">&times;</button>
                            </div>
                        `;
                                existingImagesContainer.appendChild(imgWrapper);
                            });
                        } else {
                            existingImagesContainer.innerHTML =
                                '<p class="col text-muted small">Belum ada gambar.</p>';
                        }
                    });

                    // Menjalankan kode setiap kali modal edit SUDAH DITUTUP.
                    editModalEl.addEventListener('hidden.bs.modal', function() {
                        // Jika flag 'imagesHaveChanged' bernilai true, refresh halaman.
                        if (imagesHaveChanged) {
                            location.reload();
                        }
                    });
                }

                // Event listener utama untuk seluruh dokumen, mencari aksi klik.
                document.addEventListener('click', function(event) {

                    // Mencari tombol hapus gambar yang paling dekat dengan target klik.
                    // Ini akan bekerja meskipun yang di-klik adalah ikon di dalam tombol.
                    const deleteButton = event.target.closest('.btn-delete-existing-image');

                    // Jika yang di-klik adalah tombol hapus gambar...
                    if (deleteButton) {
                        event.preventDefault();

                        // [FIX] Logika Mencegah Hapus Gambar Terakhir
                        const container = document.getElementById('existingImagesContainer');
                        const imageCount = container.querySelectorAll('.image-wrapper').length;

                        if (imageCount <= 1) {
                            Swal.fire('Aksi Ditolak!', 'Minimal harus ada satu gambar untuk setiap barang.',
                                'error');
                            return; // Hentikan fungsi di sini
                        }

                        // Jika gambar lebih dari 1, lanjutkan proses hapus...
                        const imageId = deleteButton.getAttribute('data-image-id');
                        const barangId = deleteButton.getAttribute('data-barang-id');
                        const filename = deleteButton.getAttribute('data-filename');

                        Swal.fire({
                            title: 'Hapus Gambar Ini?',
                            text: "Aksi ini tidak dapat dibatalkan!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetch(`/admin/barang/gambar/${barangId}/${filename}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]').getAttribute(
                                                'content'),
                                            'Accept': 'application/json'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            // Hapus elemen gambar dari tampilan secara visual
                                            document.getElementById(`image-wrapper-${imageId}`)
                                                .remove();
                                            // [PENTING] Naikkan bendera bahwa ada perubahan
                                            imagesHaveChanged = true;
                                            Swal.fire({
                                                toast: true,
                                                position: 'top-end',
                                                icon: 'success',
                                                title: data.message,
                                                showConfirmButton: false,
                                                timer: 2000
                                            });
                                        } else {
                                            Swal.fire('Gagal!', "Internal Server Error" ||
                                                'Gagal menghapus gambar.', 'error');
                                        }
                                    });
                            }
                        });
                    }
                });

            });
        </script>
    @endpush
@endsection
