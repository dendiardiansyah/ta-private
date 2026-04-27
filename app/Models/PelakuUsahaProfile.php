<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PelakuUsahaProfile extends Model
{
    use HasFactory;

    protected $table = 'pelaku_usaha_profiles';

    protected $fillable = [
        'user_id',
        'nama_usaha',
        'alamat',
        'nomor_telepon',
        'legacy_pelaku_usaha_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
