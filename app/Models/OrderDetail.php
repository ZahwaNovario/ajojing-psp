<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'barang_id',
        'nama_barang',
        'kuantitas',
        'harga_saat_pembelian',
    ];

    /**
     * Relasi ke induk Order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi ke produk/barang.
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
