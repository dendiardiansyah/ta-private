<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poin extends Model
{
    use HasFactory;

    
    protected $table = 'poin';

    public $timestamps = false;

    
    protected $primaryKey = 'poin_id';

   
    protected $fillable = [
        'nasabah_id',
        'transaksi_id',
        'jumlah_poin',
        'tanggal_diberikan',
    ];

   
    public function user()
    {
        return $this->belongsTo(User::class, 'nasabah_id', 'id');
    }

    
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id', 'transaksi_id');
    }
}
