<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Tag extends Model
{
     use SoftDeletes;

    protected $table = 'tags';

    protected $fillable = ['name', 'slug','description'];

    protected static function booted()
    {
        static::creating(function ($tag) {
            $slug = Str::slug($tag->name, '-');

            // Fetch all existing slugs
            $existingSlugs = Tag::pluck('slug')->toArray();

            // Check if the generated slug already exists
            $counter = 1;
            while (in_array($slug, $existingSlugs)) {
                $slug = Str::slug($tag->name, '-') . '-' . $counter;
                $counter++;
            }

            $tag->slug = $slug;
        });

        static::updating(function ($tag) {
            $slug = Str::slug($tag->name, '-');

            // Fetch all existing slugs excluding the current category
            $existingSlugs = Tag::where('id', '!=', $tag->id)->pluck('slug')->toArray();

            // Check if the generated slug already exists
            $counter = 1;
            while (in_array($slug, $existingSlugs)) {
                $slug = Str::slug($tag->name, '-') . '-' . $counter;
                $counter++;
            }

            $tag->slug = $slug;
        });

    }

    public function blogs()
    {
        return $this->belongsToMany('App\Models\Blog', 'tag_blog')->withPivot('blog_id');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'tag_user')->withPivot('user_id');
    }
}
