<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = ['nama', 'deskripsi', 'stok', 'harga'];

    public function images()
    {
        return $this->hasMany(Image::class)->orderBy('urutan');
    }
}
