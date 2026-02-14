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
    use HasFactory, InteractsWithMedia, Slugify, SoftDeletes;

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

    protected $with = ['tieredPrices'];

    public function getImageAttribute()
    {
        return $this->hasMedia('image') ? $this->getMedia('image')[0]->getFullUrl() : asset('/default-png-min.png') ;
    }

    public function getImagesAttribute()
    {
        return $this->hasMedia('images') ? $this->getMedia('images') : null;
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'tag_product')->withPivot('tag_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
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

    /**
     * Get the tiered prices for the product.
     */
    public function tieredPrices()
    {
        return $this->hasMany(ProductPrice::class)->orderBy('quantity', 'asc');
    }

    /**
     * Get price based on quantity
     * Returns the appropriate price for the given quantity (finds the best applicable tier)
     */
    public function getPriceForQuantity($quantity = 1)
    {
        // Get the appropriate tier directly with database query
        // We need to reorder since the relationship has default ascending order
        // $tieredPrice = $this->tieredPrices()
        //     ->where('quantity', '<=', $quantity)
        //     ->reorder() // Remove the default ordering from the relationship
        //     ->orderBy('quantity', 'desc')
        //     ->first();

        // // If no tier found (quantity smaller than all tiers), return base price
        // return $tieredPrice ? $tieredPrice->price : $this->price;

        $tier = $this->tieredPrices() ->where('quantity', '<=', $quantity) ->reorder() ->orderBy('quantity', 'desc') ->first(); 
        // No tier found → unit price = 0 
        if (! $tier) { return 0; } 
        
        // Convert stored total price into unit price 
        return $tier->price / $tier->quantity;
    }

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/jpg']);
        // ->maxFilesize(2048); // 2MB max file size
    }

    /**
     * Get exact tier price for a specific quantity tier
     * Used when user selects a specific tier from dropdown
     */
    public function getExactTierPrice($quantity)
    {
        // Find the exact tier for this quantity
        $tieredPrice = $this->tieredPrices()
            ->where('quantity', $quantity)
            ->first();

        // If exact tier not found, fall back to the best price for this quantity
        return $tieredPrice ? $tieredPrice->price : $this->getPriceForQuantity($quantity);
    }

    /**
     * Get all available price tiers
     */
    public function getPriceTiers()
    {
        return $this->tieredPrices()->get();
    }
}
