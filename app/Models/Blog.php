<?php

namespace App\Models;

use App\Traits\Slugify;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Blog extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes, Slugify;

    protected $fillable = ['title', 'slug', 'description', 'short_description', 'status', 'author'];

    public function getImageAttribute()
    {
        return $this->hasMedia('image') ? $this->getMedia('image')[0]->getFullUrl() : '';
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'tag_blog')->withPivot('tag_id');
    }


}
