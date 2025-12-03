<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'price',
    ];

    /**
     * Get the product that owns the price.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope to get prices for a specific quantity or less
     */
    public function scopeForQuantity($query, $quantity)
    {
        return $query->where('quantity', '<=', $quantity)
                    ->orderBy('quantity', 'desc')
                    ->limit(1);
    }
}
