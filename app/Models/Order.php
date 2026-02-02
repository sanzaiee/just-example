<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'pid',
        'amount',
        'quantity',
        'discount',
        'user_id',
        'pay_status',
        'pending_status',
        'cancel_status',
        'delivery_status',
        'order_status',
        'is_store_pickup',
        'notes'
    ];


    public function orderProductLists()
    {
        return $this->hasMany(OrderProductList::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderCancel()
    {
        return $this->hasOne(OrderCancel::class);
    }

    public function orderDeliveryAddress()
    {
        return $this->hasOne(OrderDeliveryAddress::class, 'order_id');
    }

    /**
     * Alias for orderDeliveryAddress for backward compatibility.
     * Use orderDeliveryAddress for new code.
     */
    public function shippingAddress()
    {
        return $this->orderDeliveryAddress();
    }
}
