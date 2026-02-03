<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Slugify
{
    protected static function booted(): void
    {
        static::creating(function ($model): void {
            if (empty($model->slug)) {
                $model->generateSlug();
            }
        });

        static::updating(function ($model): void {
            // Do not change slug on normal updates; only generate if it's missing
            if (empty($model->slug)) {
                $model->generateSlug();
            }
        });
    }

    protected function generateSlug(): void
    {
        $source = $this->title ?? $this->name;

        if (empty($source)) {
            return;
        }

        $slug = Str::slug($source);

        // Ensure uniqueness; ignore current model when updating
        $query = static::where('slug', $slug);

        if ($this->exists && $this->getKey()) {
            $query->whereKeyNot($this->getKey());
        }

        $existingSlug = $query->first();

        if ($existingSlug) {
            $countQuery = static::where('slug', 'like', $slug . '%');

            if ($this->exists && $this->getKey()) {
                $countQuery->whereKeyNot($this->getKey());
            }

            $count = $countQuery->count();
            $slug .= '-' . ($count + 1);
        }

        $this->slug = $slug;
    }
}
