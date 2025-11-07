<?php

namespace App\Models;

use App\Traits\Slugify;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class OurTeam extends Model implements HasMedia
{
    use HasFactory,SoftDeletes,InteractsWithMedia,Slugify;

    protected $fillable = [
       'title','slug',
       'post','short_description',
        'description',
        'position',
        'status',
        'facebook',
        'instagram',
        'youtube',
        'linkedin',
        'twitter',
    ];

    public function getImageAttribute()
    {
        return $this->hasMedia('image') ? $this->getMedia('image')[0]->getFullUrl() : '';
    }
}
