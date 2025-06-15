<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus folder barang lama jika ada untuk membersihkan
        Storage::deleteDirectory('public/barang');

        // Data contoh
        $produks = [
            ['nama' => 'Kemeja Flanel Kotak-kotak', 'deskripsi' => 'Kemeja flanel bahan katun premium, nyaman dipakai sehari-hari.', 'stok' => 50, 'harga' => 150000],
            ['nama' => 'Celana Chino Slim Fit', 'deskripsi' => 'Celana chino dengan potongan slim fit modern, bahan stretch.', 'stok' => 45, 'harga' => 185000],
            ['nama' => 'Kaos Polos Cotton Combed 30s', 'deskripsi' => 'Kaos polos basic dengan bahan katun combed 30s yang adem.', 'stok' => 120, 'harga' => 65000],
            ['nama' => 'Sneakers Kanvas Klasik', 'deskripsi' => 'Sepatu sneakers model klasik yang tak lekang oleh waktu.', 'stok' => 30, 'harga' => 250000],
            ['nama' => 'Jaket Hoodie Fleece', 'deskripsi' => 'Jaket hoodie tebal dengan bahan fleece yang hangat.', 'stok' => 40, 'harga' => 220000],
            ['nama' => 'Topi Baseball Polos', 'deskripsi' => 'Topi baseball dengan desain minimalis untuk gaya kasual.', 'stok' => 80, 'harga' => 50000],
            ['nama' => 'Tas Ransel Laptop Anti Air', 'deskripsi' => 'Tas ransel untuk laptop hingga 15 inci, bahan waterproof.', 'stok' => 25, 'harga' => 350000],
            ['nama' => 'Jam Tangan Analog Kulit', 'deskripsi' => 'Jam tangan analog dengan strap kulit asli, desain elegan.', 'stok' => 15, 'harga' => 450000],
        ];

        foreach ($produks as $produk) {
            // Buat record barang
            $barang = Barang::create($produk);

            // Buat folder untuk gambar
            $folderPath = storage_path("app/public/barang/{$barang->id}");
            File::makeDirectory($folderPath, 0755, true, true);

            // Buat 1-3 record gambar contoh untuk setiap barang
            $jumlahGambar = rand(1, 3);
            for ($i = 1; $i <= $jumlahGambar; $i++) {
                Image::create([
                    'barang_id' => $barang->id,
                    'path' => "barang/{$barang->id}/placeholder.jpg", // Path placeholder
                    'alt_text' => "Gambar {$i} untuk {$barang->nama}",
                    'is_utama' => ($i == 1),
                    'urutan' => $i
                ]);
            }
        }
    }
}
