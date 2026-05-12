<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelaku_usaha_id',
        'name',
        'description',
        'price_rupiah',
        'stock',
        'image_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price_rupiah' => 'integer',
        'stock' => 'integer',
    ];

    public function pelakuUsaha(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pelaku_usaha_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(ProductOrder::class);
    }
}
