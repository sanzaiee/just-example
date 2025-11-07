<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate([
            'attribute' => 'contact_banner_image',
        ], [
            'setting_group_slug' => 'contact-us-information',
            'attribute' => 'contact_banner_image',
            'field_name' => 'Banner Image',
            'field_type' => 'file',
        ]);

        Setting::updateOrCreate([
            'attribute' => 'contact_banner_text',
        ], [
            'setting_group_slug' => 'contact-us-information',
            'attribute' => 'contact_banner_text',
            'field_name' => 'Banner Text',
            'field_type' => 'text',
        ]);

        Setting::updateOrCreate([
            'attribute' => 'contact_facebook',
        ], [
            'setting_group_slug' => 'contact-us-information',
            'attribute' => 'contact_facebook',
            'field_name' => 'Facebook Link',
            'field_type' => 'text',
        ]);

        Setting::updateOrCreate([
            'attribute' => 'contact_twitter',
        ], [
            'setting_group_slug' => 'contact-us-information',
            'attribute' => 'contact_twitter',
            'field_name' => 'Twitter Link',
            'field_type' => 'text',
        ]);

        Setting::updateOrCreate([
            'attribute' => 'contact_linkedin',
        ], [
            'setting_group_slug' => 'contact-us-information',
            'attribute' => 'contact_linkedin',
            'field_name' => 'Linkedin Link',
            'field_type' => 'text',
        ]);

        Setting::updateOrCreate([
            'attribute' => 'contact_youtube',
        ], [
            'setting_group_slug' => 'contact-us-information',
            'attribute' => 'contact_youtube',
            'field_name' => 'Youtube Link',
            'field_type' => 'text',
        ]);

        Setting::updateOrCreate([
            'attribute' => 'contact_map_link',
        ], [
            'setting_group_slug' => 'contact-us-information',
            'attribute' => 'contact_map_link',
            'field_name' => 'Location [Google map IFRAME link]',
            'field_type' => 'text',
        ]);

    }
}
