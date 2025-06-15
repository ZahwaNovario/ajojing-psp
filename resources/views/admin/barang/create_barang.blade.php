<div class="modal fade" id="createBarangModal" tabindex="-1" aria-labelledby="createBarangModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createBarangModalLabel">Tambah Barang Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Nama Barang --}}
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Barang</label>
                        <input type="text" name="nama" class="form-control" id="nama"
                            placeholder="Contoh: Baju Kemeja Polos" required>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" id="deskripsi" rows="3"
                            placeholder="Jelaskan detail barang di sini" required></textarea>
                    </div>

                    {{-- Stok & Harga dalam satu baris --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="stok" class="form-label">Stok</label>
                            <input type="number" name="stok" class="form-control" id="stok" placeholder="0"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="harga" class="form-label">Harga (Rp)</label>
                            <input type="number" name="harga" class="form-control" id="harga" placeholder="50000"
                                required>
                        </div>
                    </div>

                    <hr>

                    {{-- Upload Gambar --}}
                    <div class="mb-3">
                        <label class="form-label">Gambar Barang (Bisa lebih dari satu)</label>
                        <div id="gambarInputsContainer">
                            <div class="input-group mb-2">
                                <input type="file" name="gambar[]" class="form-control" accept="image/*" required>
                                {{-- Tombol hapus tidak ditampilkan untuk input pertama --}}
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="addGambarInput()">
                            <i class="ti ti-plus"></i> Tambah Input Gambar
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Barang</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{--
    Menaruh script di @push agar lebih rapi.
    Pastikan di layout utama (app.blade.php) Anda ada @stack('scripts') sebelum </body>
--}}
@push('scripts')
    <script>
        function addGambarInput() {
            const container = document.getElementById('gambarInputsContainer');

            const wrapper = document.createElement('div');
            wrapper.classList.add('input-group', 'mb-2');

            const input = document.createElement('input');
            input.type = 'file';
            input.name = 'gambar[]';
            input.accept = 'image/*';
            input.classList.add('form-control');

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.classList.add('btn', 'btn-outline-danger');
            btn.innerHTML = '<i class="ti ti-trash"></i>'; // Menggunakan ikon untuk konsistensi
            btn.onclick = () => {
                wrapper.remove();
            };

            wrapper.appendChild(input);
            wrapper.appendChild(btn);
            container.appendChild(wrapper);
        }
    </script>
@endpush
