<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDeliveryAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'name',
        'email',
        'address',
        'street',
        'city',
        'tole',
        'house_no',
        'phone',
        'description',
        'status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
