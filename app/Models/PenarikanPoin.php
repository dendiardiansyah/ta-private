<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenarikanPoin extends Model
{
    use HasFactory;

    protected $table = 'penarikan_poin';

    protected $fillable = [
        'nasabah_id',
        'jumlah_poin',
        'jumlah_uang',
        'status_penarikan',
        'tanggal_penarikan',
    ];

    // Relasi ke user
    public function nasabah()
    {
        return $this->belongsTo(User::class, 'nasabah_id');
    }
}
