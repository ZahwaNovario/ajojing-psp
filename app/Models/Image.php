<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'barang_id',
        'path',
        'alt_text',
        'urutan',
        'is_utama',
    ];
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
