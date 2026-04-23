<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PelakuUsaha extends Authenticatable
{
    use HasFactory;

   
    protected $table = 'pelaku_usaha';

   
    protected $primaryKey = 'pelaku_usaha_id';

    
    protected $fillable = ['nama', 'password', 'alamat', 'nomor_telepon'];

    
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
