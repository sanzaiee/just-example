<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate([
            'attribute' => 'site_name',
        ], [
            'setting_group_slug' => 'general-information',
            'attribute' => 'site_name',
            'field_name' => 'Site Name',
            'field_type' => 'text',
        ]);

        Setting::updateOrCreate([
            'attribute' => 'logo',
        ], [
            'setting_group_slug' => 'general-information',
            'attribute' => 'logo',
            'field_name' => 'Site Logo',
            'field_type' => 'file',
        ]);

        Setting::updateOrCreate([
            'attribute' => 'fav',
        ], [
            'setting_group_slug' => 'general-information',
            'attribute' => 'fav',
            'field_name' => 'Site Fav Icon',
            'field_type' => 'file',
        ]);


        Setting::updateOrCreate([
            'attribute' => 'working_time',
        ], [
            'setting_group_slug' => 'general-information',
            'attribute' => 'working_time',
            'field_name' => 'Working Time',
            'field_type' => 'text',
        ]);
        Setting::updateOrCreate([
            'attribute' => 'phone',
        ], [
            'setting_group_slug' => 'general-information',
            'attribute' => 'phone',
            'field_name' => 'Phone',
            'field_type' => 'text',
        ]);
        Setting::updateOrCreate([
            'attribute' => 'email',
        ], [
            'setting_group_slug' => 'general-information',
            'attribute' => 'email',
            'field_name' => 'Email',
            'field_type' => 'text',
        ]);

        Setting::updateOrCreate([
            'attribute' => 'official_email',
        ], [
            'setting_group_slug' => 'general-information',
            'attribute' => 'official_email',
            'field_name' => 'Official Email',
            'field_type' => 'text',
        ]);

        Setting::updateOrCreate([
            'attribute' => 'official_phone',
        ], [
            'setting_group_slug' => 'general-information',
            'attribute' => 'official_phone',
            'field_name' => 'Official Phone',
            'field_type' => 'text',
        ]);
    }
}
