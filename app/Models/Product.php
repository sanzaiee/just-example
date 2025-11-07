<?php

namespace App\Models;

use App\Traits\Slugify;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes, Slugify;

    protected $fillable = [
            'category_id',
            'user_id',
            'brand_id',
            'tag_id',
            'name',
            'slug',
            'code',
            'video_url',
            'short',
            'description',
            'price',
            'strike_price',
            'tax',
            'delivery_charges',
            'view_count',
            'warrenty_period',
            'warrenty_policy',
            'warrenty_type',
            'on_sale',
            'best_rated',
            'feature',
            'home_delivery',
            'stock',
            'status',
            'meta_title',
            'meta_keyword',
            'meta_description',
    ];

    public function getImageAttribute()
    {
        return $this->hasMedia('image') ? $this->getMedia('image')[0]->getFullUrl() : '';
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'tag_product')->withPivot('tag_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function relatedProducts()
    {
        return $this->belongsToMany(
            Product::class,
            'related_product',
            'product_id',
            'related_id'
        );
    }
}
