<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubMenuSetup extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id', 'title', 'action', 'attribute', 'target', 'tooltip', 'icon', 'enabled', 'position'
    ];

    public function child()
    {
        return $this->hasMany(SubMenuSetup::class, 'parent_id');
    }
}
