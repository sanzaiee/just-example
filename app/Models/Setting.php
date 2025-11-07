<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Setting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'setting_name', 'setting_group_slug', 'field_name', 'field_type', 'attribute', 'value'
    ];

    public function getImage($image){
        return $this->hasMedia($image) ? $this->getFirstMediaUrl($image) : '';
    }

    public function getBannerImage($image){
        $attribute = $image . '_banner_image';
        return $this->hasMedia($attribute) ? $this->getFirstMediaUrl($attribute) : '';
    }

}
