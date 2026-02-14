<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderFulfillmentEvents extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'event_type',
        'status',
        'raw_payload',
        'message',
    ];

    protected $casts = ['raw_payload' => 'array',];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
