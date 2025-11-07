<?php

namespace App\Models;

use App\Traits\Slugify;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SubPages extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Slugify;

    protected $fillable = [
        'sub_title', 'title','slug','short_description','description','status','position','page_id'
     ];

     public function getImageAttribute()
     {
         return $this->hasMedia('image') ? $this->getMedia('image')[0]->getFullUrl() : '';
     }

     public function getImagesAttribute()
     {
         return $this->hasMedia('image') ? $this->getMedia('image') : '';
     }
}
