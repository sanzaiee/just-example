<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Brand extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia,SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'position',
        'description',
        'status',
        'menu',
    ];

    public function getImageAttribute()
    {
        return $this->hasMedia('image') ? $this->getMedia('image')[0]->getFullUrl() : '';
    }

    protected static function booted()
    {
        static::creating(function ($brand) {
            $slug = Str::slug($brand->name, '-');

            // Fetch all existing slugs
            $existingSlugs = Brand::pluck('slug')->toArray();

            // Check if the generated slug already exists
            $counter = 1;
            while (in_array($slug, $existingSlugs)) {
                $slug = Str::slug($brand->name, '-').'-'.$counter;
                $counter++;
            }

            $brand->slug = $slug;
        });

        static::updating(function ($brand) {
            $slug = Str::slug($brand->name, '-');

            // Fetch all existing slugs excluding the current brand
            $existingSlugs = Brand::where('id', '!=', $brand->id)->pluck('slug')->toArray();

            // Check if the generated slug already exists
            $counter = 1;
            while (in_array($slug, $existingSlugs)) {
                $slug = Str::slug($brand->name, '-').'-'.$counter;
                $counter++;
            }

            $brand->slug = $slug;
        });

    }
}
