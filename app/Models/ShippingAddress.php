<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;

    protected $fillable = [
           'user_id',
           'type',
           'name',
           'email',
           'address',
           'street',
           'city',
           'tole',
           'house_no',
           'phone',
           'description',
           'postal_code',
           'latitude',
           'longitude',
           'active'
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
