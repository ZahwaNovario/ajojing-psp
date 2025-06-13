<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = Barang::all();
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
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'gambar.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $barang = Barang::create($request->only('nama', 'deskripsi', 'stok', 'harga'));

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs("barang/{$barang->id}", $filename, 'public');
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
            foreach ($request->file('gambar') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs("barang/{$barang->id}", $filename, 'public');
            }
        }

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function uploadImage(Request $request, $id)
    {
        $request->validate([
            'gambar.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $barang = Barang::findOrFail($id);

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs("barang/{$barang->id}", $filename, 'public');
            }
        }

        return back()->with('success', 'Gambar berhasil diupload.');
    }


    public function deleteImage($id, $filename)
    {
        $path = "barang/{$id}/{$filename}";

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        return back()->with('success', 'Gambar berhasil dihapus.');
    }
}
