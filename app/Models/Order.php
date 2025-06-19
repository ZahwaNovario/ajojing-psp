<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * 
 *
 * @property int $id
 * @property string|null $uuid
 * @property string $kode
 * @property int|null $user_id
 * @property int|null $processed_by_user_id
 * @property string $status
 * @property int $total_harga
 * @property string $alamat_pengiriman
 * @property string|null $nomor_resi
 * @property string|null $catatan_pembeli
 * @property string|null $bukti_pembayaran
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderDetail> $details
 * @property-read int|null $details_count
 * @property-read \App\Models\User|null $processor
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereAlamatPengiriman($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereBuktiPembayaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCatatanPembeli($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereNomorResi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereProcessedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUuid($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    /**
     * [BARU] Beritahu Eloquent bahwa 'id' tetap auto-increment.
     * @var bool
     */
    public $incrementing = true;

    /**
     * [BARU] Beritahu Eloquent bahwa tipe data primary key adalah integer.
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Kolom yang boleh diisi secara massal (mass assignable).
     * Pastikan 'uuid' tidak ada di sini karena akan diisi otomatis.
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
        'bukti_pembayaran',
    ];
    /**
     * [PENTING] Beritahu trait HasUuids kolom mana saja yang harus diisi UUID.
     *
     * @return array
     */
    public function uniqueIds()
    {
        return ['uuid']; // <-- Bukan 'id', tapi 'uuid'
    }
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

    /**
     * Beritahu Laravel untuk menggunakan 'uuid' saat mencari di route.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected static function booted()
    {
        static::creating(function ($order) {
            $order->kode = static::generateKode();
        });
    }

    public static function generateKode()
    {
        do {
            $prefix = 'ORD';
            $tanggal = now()->format('Ymd');
            $random = strtoupper(Str::random(6));
            $kode = "{$prefix}-{$tanggal}-{$random}";
        } while (self::where('kode', $kode)->exists());

        return $kode;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'nomor_resi']) // Kita ingin tahu saat status & resi berubah
            ->setDescriptionForEvent(fn(string $eventName) => "Pesanan ini telah di-{$eventName}")
            ->useLogName('Order');
    }
}
