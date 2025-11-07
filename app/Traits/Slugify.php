<?php

namespace App\Traits;
use Illuminate\Support\Str;

trait Slugify
{

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->generateSlug();
        });

        static::updating(function ($model) {
            $model->generateSlug();
        });
    }

    protected function generateSlug()
    {
        $slug = Str::slug($this->title ?? $this->name);

        $existingSlug = static::where('slug', $slug)->first();

        if ($existingSlug) {
            $count = static::where('slug', 'like', $slug.'%')->count();
            $slug .= '-' . ($count + 1);
        }

        $this->slug = $slug;
    }
}
