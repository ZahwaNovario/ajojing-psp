<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Gunakan Eager Loading untuk efisiensi
        $barangs = Barang::with('images')->latest()->get();
        return view('admin.barang.index', compact('barangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'gambar' => 'nullable|array', // Sebaiknya 'required' jika gambar wajib
            'gambar.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $barang = Barang::create($request->only('nama', 'deskripsi', 'stok', 'harga'));

        if ($request->hasFile('gambar')) {
            // Kita gunakan $key untuk melacak urutan gambar
            foreach ($request->file('gambar') as $key => $file) {
                // Membuat nama file unik (kode Anda sudah bagus)
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Menyimpan file fisik
                $file->storeAs("barang/{$barang->id}", $filename, 'public');

                // ==========================================================
                // BAGIAN INI DITAMBAHKAN UNTUK MENYIMPAN KE DATABASE IMAGES
                // ==========================================================
                Image::create([
                    'barang_id' => $barang->id,
                    'path' => "barang/{$barang->id}/{$filename}", // Simpan path bersihnya
                    'alt_text' => $validated['nama'], // Gunakan nama barang sebagai alt text
                    'is_utama' => $key == 0, // Tandai gambar pertama sebagai gambar utama
                    'urutan' => $key + 1, // Atur urutan berdasarkan perulangan
                ]);
            }
        }

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'gambar.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($request->only('nama', 'deskripsi', 'stok', 'harga'));

        if ($request->hasFile('gambar')) {

            // Ambil urutan terakhir dari gambar yang sudah ada untuk melanjutkan penomoran
            $lastOrder = $barang->images()->max('urutan') ?? 0;

            foreach ($request->file('gambar') as $key => $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Simpan file fisik ke storage
                $file->storeAs("barang/{$barang->id}", $filename, 'public');

                // =======================================================
                // [INI BAGIAN YANG HILANG SEBELUMNYA]
                // Buat record baru di tabel 'images' untuk setiap gambar baru
                // =======================================================
                Image::create([
                    'barang_id' => $barang->id,
                    'path'      => "barang/{$barang->id}/{$filename}",
                    'alt_text'  => $barang->name,
                    'urutan'    => $lastOrder + $key + 1, // Lanjutkan urutan
                ]);
            }
        }

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        // Tentukan nama direktori di dalam disk 'public'
        $directory = 'barang/' . $barang->id;

        // Gunakan Storage::disk('public') agar lebih jelas dan aman.
        // Cek dulu apakah direktori benar-benar ada sebelum mencoba menghapus.
        if (Storage::disk('public')->exists($directory)) {
            // Hapus direktori beserta semua isinya dari disk 'public'.
            Storage::disk('public')->deleteDirectory($directory);
        }

        // Hapus data barang dari database.
        // Ini akan otomatis menghapus data di tabel 'images' juga (karena cascade).
        $barang->delete();

        // Redirect kembali dengan pesan sukses.
        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus.');
    }

    // public function uploadImage(Request $request, $id)
    // {
    //     $request->validate([
    //         'gambar.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    //     ]);

    //     $barang = Barang::findOrFail($id);

    //     if ($request->hasFile('gambar')) {
    //         foreach ($request->file('gambar') as $file) {
    //             $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    //             $file->storeAs("barang/{$barang->id}", $filename, 'public');
    //         }
    //     }

    //     return back()->with('success', 'Gambar berhasil diupload.');
    // }


    public function deleteImage($id, $filename)
    {
        try {
            // Cari record gambar di database berdasarkan barang_id dan nama file
            $image = Image::where('barang_id', $id)
                ->where('path', 'like', "%{$filename}")
                ->firstOrFail(); // Gunakan firstOrFail untuk error handling otomatis jika tidak ketemu

            // Hapus file fisik dari storage
            Storage::delete('public/' . $image->path);

            // Hapus record dari database
            $image->delete();

            // [PENTING] Kembalikan respons JSON yang menandakan sukses
            return response()->json(['success' => true, 'message' => 'Gambar berhasil dihapus.']);
        } catch (\Exception $e) {
            // [PENTING] Kembalikan respons JSON jika terjadi error
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


}
