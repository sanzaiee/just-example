<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable =['name','slug','parent_id','position','description','status','menu'];

    protected static function booted()
    {
        static::creating(function ($category) {
            $slug = Str::slug($category->name, '-');

            // Fetch all existing slugs
            $existingSlugs = Category::pluck('slug')->toArray();

            // Check if the generated slug already exists
            $counter = 1;
            while (in_array($slug, $existingSlugs)) {
                $slug = Str::slug($category->name, '-') . '-' . $counter;
                $counter++;
            }

            $category->slug = $slug;
        });

        static::updating(function ($category) {
            $slug = Str::slug($category->name, '-');

            // Fetch all existing slugs excluding the current category
            $existingSlugs = Category::where('id', '!=', $category->id)->pluck('slug')->toArray();

            // Check if the generated slug already exists
            $counter = 1;
            while (in_array($slug, $existingSlugs)) {
                $slug = Str::slug($category->name, '-') . '-' . $counter;
                $counter++;
            }

            $category->slug = $slug;
        });

    }

    public function parent(){
        return $this->belongsTo(Category::class,'parent_id');
    }

    public function child(){
        return $this->hasMany(Category::class,'parent_id');
    }

//    Primary Level -> Class Three -> Subjects -> Chapter -> [Content,Others,FAQs]
//    Teacher -> Level -> Classes{online,physical} [three -> [Nepali,Time and all] -> [Remarks of Day, Tomorrows Plan]]
//    Students ->Class ->Subjects -> Chapter

    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
