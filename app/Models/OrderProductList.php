<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProductList extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity', 'order_id', 'product_id', 'status', 'notes', 'price',
        'product_name', 'product_code', 'product_description'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        // return $this->belongsTo(Product::class);
        return $this->belongsTo(Product::class)->withTrashed();
    }

    /**
     * Get the product name, using historical data if available,
     * otherwise falling back to the current product data.
     *
     * @return string|null
     */
    public function getProductName(): ?string
    {
        if (!empty($this->product_name)) {
            return $this->product_name;
        }

        return $this->product?->name;
    }

    /**
     * Get the product code, using historical data if available,
     * otherwise falling back to the current product data.
     *
     * @return string|null
     */
    public function getProductCode(): ?string
    {
        if (!empty($this->product_code)) {
            return $this->product_code;
        }

        return $this->product?->code;
    }

    /**
     * Get the product description, using historical data if available,
     * otherwise falling back to the current product data.
     *
     * @return string|null
     */
    public function getProductDescription(): ?string
    {
        if (!empty($this->product_description)) {
            return $this->product_description;
        }

        return $this->product?->short;
    }
}
