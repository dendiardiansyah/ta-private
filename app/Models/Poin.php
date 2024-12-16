<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poin extends Model
{
    use HasFactory;

    // Tentukan tabel yang digunakan oleh model ini (optional, jika berbeda dari nama model)
    protected $table = 'poin';

    public $timestamps = false;

    // Tentukan primary key yang digunakan (karena bukan 'id')
    protected $primaryKey = 'poin_id';

    // Tentukan atribut yang bisa diisi (mass assignable)
    protected $fillable = [
        'nasabah_id',
        'transaksi_id',
        'jumlah_poin',
        'tanggal_diberikan',
    ];

    /**
     * Relasi dengan model User (Nasabah)
     * 
     * Mengembalikan data nasabah yang menerima poin.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'nasabah_id', 'id');
    }

    /**
     * Relasi dengan model Transaksi
     * 
     * Mengembalikan data transaksi yang terkait dengan poin ini.
     */
    // app/Models/Poin.php
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id', 'transaksi_id');
    }
}
