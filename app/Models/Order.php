<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal (mass assignable).
     * @var array
     */
    protected $fillable = [
        'user_id',
        'processed_by_user_id',
        'status',
        'total_harga',
        'alamat_pengiriman',
        'nomor_resi',
        'catatan_pembeli',
    ];

    /**
     * Relasi ke User (customer yang memesan).
     * Nama method 'user' berarti kita bisa panggil $order->user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke User (pegawai/admin yang memproses).
     * Nama method 'processor' berarti kita bisa panggil $order->processor
     */
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by_user_id');
    }

    /**
     * Relasi ke rincian item di dalam order ini.
     * Nama method 'details' berarti kita bisa panggil $order->details
     */
    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
