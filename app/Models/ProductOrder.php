<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductOrder extends Model
{
    use HasFactory;

    protected $table = 'product_orders';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'unit_price_rupiah',
        'total_price_rupiah',
        'points_spent',
        'status',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price_rupiah' => 'integer',
        'total_price_rupiah' => 'integer',
        'points_spent' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
