<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSampah extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'jenis_sampah';
    protected $primaryKey = 'jenis_sampah_id';

    // Kolom yang boleh diisi melalui mass assignment
    protected $fillable = [
        'nama_jenis',
        'deskripsi',
    ];

    // Jika tabel tidak memiliki kolom timestamps (created_at, updated_at)
    public $timestamps = false;

    /**
     * Relasi ke model Transaksi (1 jenis sampah bisa memiliki banyak transaksi)
     */
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'jenis_sampah_id');
    }
}
