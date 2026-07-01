<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'transaksi_id';
    public $timestamps = false;

    protected $fillable = [
        'nasabah_id',
        'petugas_id',
        'alamat_penjemputan',
        'tanggal_transaksi',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'nasabah_id');  // Nasabah_id adalah foreign key yang merujuk ke tabel users
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    /**
     * Get all detail items for this transaksi (multiple jenis sampah with berat).
     */
    public function details()
    {
        return $this->hasMany(TransaksiDetail::class, 'transaksi_id', 'transaksi_id');
    }

    /**
     * @deprecated Use details() instead. This relationship is no longer valid after schema refactor.
     */
    public function jenisSampah()
    {
        return $this->belongsTo(JenisSampah::class, 'jenis_sampah_id');
    }

    public function poin()
    {
        return $this->hasMany(Poin::class, 'transaksi_id', 'transaksi_id');
    }

    /**
     * Calculate total berat from all detail items.
     */
    public function getTotalBeratAttribute()
    {
        return $this->details->sum('berat');
    }

}
