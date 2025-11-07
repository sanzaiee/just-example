<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Banner extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia,SoftDeletes;

    protected $fillable = ['name', 'position', 'description', 'short_description', 'status', 'link','button'];

    public function getImageAttribute()
    {
        return $this->hasMedia('image') ? $this->getMedia('image')[0]->getFullUrl() : '';
    }
}
