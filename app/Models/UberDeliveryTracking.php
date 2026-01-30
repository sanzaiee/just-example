<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UberDeliveryTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'tracking_number',
        'status',
        'message',
        'tracking_url',
        'delivery_id',
        'delivery_status',
        'delivery_message',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
