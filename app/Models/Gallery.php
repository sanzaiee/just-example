<?php

namespace App\Models;

use App\Traits\Slugify;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Gallery extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Slugify;

    protected $fillable = [
        'name','slug','description','status'
    ];

    public function getImageAttribute()
    {
        return $this->hasMedia('image') ? $this->getMedia('image')[0]->getFullUrl() : '';
    }

    public function getImagesAttribute()
    {
        return $this->hasMedia('image') ? $this->getMedia('image') : '';
    }


    // public function tags()
    // {
    //     return $this->belongsToMany('App\Models\Tag', 'tag_gallery')->withPivot('tag_id');
    // }

}
