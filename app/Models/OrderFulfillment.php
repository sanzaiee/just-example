<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\User;

class OrderFulfillment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'lockbox_number',
        'tracking_number',
        'delivery_partner',
        'status',
        'tracking_url',
        'message',
        'created_by'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function Creator()
    {
        return $this->belongsTo(User::class);
    }
}
