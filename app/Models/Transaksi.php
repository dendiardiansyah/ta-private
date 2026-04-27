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
        'jenis_sampah_id',
        'alamat_penjemputan',
        'jumlah',
        'tanggal_transaksi',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'nasabah_id');  // Nasabah_id adalah foreign key yang merujuk ke tabel users
    }
    public function jenisSampah()
    {
        return $this->belongsTo(JenisSampah::class, 'jenis_sampah_id');
    }
    public function poin()
    {
        return $this->hasMany(Poin::class, 'transaksi_id', 'transaksi_id');
    }

}
