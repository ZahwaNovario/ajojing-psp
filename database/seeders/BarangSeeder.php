<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Barang::create([
            'nama' => 'Kemeja Polos',
            'deskripsi' => 'Kemeja lengan panjang warna putih polos.',
            'stok' => 20,
            'harga' => 150000,
        ]);

        Barang::create([
            'nama' => 'Celana Jeans',
            'deskripsi' => 'Celana jeans biru slim fit.',
            'stok' => 15,
            'harga' => 200000,
        ]);

        Barang::create([
            'nama' => 'Sepatu Sneakers',
            'deskripsi' => 'Sneakers hitam ukuran 42.',
            'stok' => 10,
            'harga' => 300000,
        ]);
    }
}
