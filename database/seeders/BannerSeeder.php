<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(['about', 'service','blog','contact','gallery'] as $name){
            Setting::updateOrCreate([
                'attribute' => $name.'_banner_image',
            ], [
                'setting_group_slug' => 'banner-information',
                'attribute' => $name.'_banner_image',
                'field_name' => ucfirst($name). ' Banner Image',
                'field_type' => 'file',
            ]);

            Setting::updateOrCreate([
                'attribute' => $name.'_banner_text',
            ], [
                'setting_group_slug' => 'banner-information',
                'attribute' => $name.'_banner_text',
                'field_name' => ucfirst($name).' Banner Text',
                'field_type' => 'text',
            ]);

            Setting::updateOrCreate([
                'attribute' => $name.'_banner_description',
            ], [
                'setting_group_slug' => 'banner-information',
                'attribute' => $name.'_banner_description',
                'field_name' => ucfirst($name) .' Banner Description',
                'field_type' => 'textarea',
            ]);
        }
    }
}
