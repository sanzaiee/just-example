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
        'lname',
        'email',
        'address',
        'street',
        'city',
        'tole',
        'house_no',
        'phone',
        'description',
        'postal_code',
        'status',
    ];

    // public function order()
    // {
    //     return $this->belongsTo(Order::class);
    // }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
