<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 *
 *
 * @property int $id
 * @property string $nama
 * @property string|null $slug
 * @property string|null $deskripsi
 * @property int $stok
 * @property string $harga
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Image> $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderDetail> $orderDetails
 * @property-read int|null $order_details_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barang query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barang whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barang whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barang whereHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barang whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barang whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barang whereStok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barang whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Barang extends Model
{
    use HasFactory, HasSlug, LogsActivity;
    protected $fillable = ['nama', 'deskripsi', 'stok', 'harga'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('nama') // Sumber slug adalah kolom 'nama'
            ->saveSlugsTo('slug');      // Disimpan ke kolom 'slug'
    }
    public function images()
    {
        return $this->hasMany(Image::class)->orderBy('urutan');
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($barang) {
            $barang->slug = Str::slug($barang->nama);
        });
    }

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly(['nama', 'stok', 'harga']) // Hanya catat perubahan pada kolom ini
    //         ->setDescriptionForEvent(fn(string $eventName) => "Produk ini telah di-{$eventName}")
    //         ->useLogName('Product');
    // }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Memberitahu Spatie untuk hanya peduli pada perubahan di kolom-kolom ini.
            ->logOnly(['nama', 'stok', 'harga', 'deskripsi'])
            // Ini akan menyimpan nilai LAMA dan BARU saat ada 'update'. Sangat penting!
            ->logOnlyDirty()
            // Membuat deskripsi log yang dinamis.
            ->setDescriptionForEvent(fn(string $eventName) => "Produk ini telah di-{$eventName}")
            // Memberi label 'Product' untuk semua log dari model ini, agar mudah difilter.
            ->useLogName('Product');
    }
}
