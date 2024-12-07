<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PelakuUsaha extends Authenticatable
{
    use HasFactory;

    // Sesuaikan nama tabel
    protected $table = 'pelaku_usaha';

    // Sesuaikan primary key jika diperlukan
    protected $primaryKey = 'pelaku_usaha_id';

    // Daftar atribut yang bisa diisi secara massal
    protected $fillable = ['nama', 'password', 'alamat', 'nomor_telepon'];

    // Hash password secara otomatis saat disimpan
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
