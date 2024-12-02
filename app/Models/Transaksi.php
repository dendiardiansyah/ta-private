<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    public $timestamps = false;

    protected $fillable = [
        'nasabah_id',
        'jenis_sampah_id',
        'pelaku_usaha_id',
        'alamat_penjemputan',
        'jumlah',
        'tanggal_transaksi',
    ];
}
