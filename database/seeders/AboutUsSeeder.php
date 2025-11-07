<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AboutUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Setting::where('setting_group_slug', 'about-us-information')->delete();

        Setting::updateOrCreate([
            'attribute' => 'about_title',
        ], [
            'setting_group_slug' => 'about-us-information',
            'attribute' => 'about_title',
            'field_name' => 'Title',
            'field_type' => 'text',
        ]);

        Setting::updateOrCreate([
            'attribute' => 'about_description',
        ], [
            'setting_group_slug' => 'about-us-information',
            'attribute' => 'about_description',
            'field_name' => 'Description',
            'field_type' => 'textarea',
        ]);

        Setting::updateOrCreate([
            'attribute' => 'about_successful_projects',
        ], [
            'setting_group_slug' => 'about-us-information',
            'attribute' => 'about_successful_projects',
            'field_name' => 'Successful Project Number',
            'field_type' => 'text',
            'value' => 0,
        ]);

        Setting::updateOrCreate([
            'attribute' => 'about_happy_customers',
        ], [
            'setting_group_slug' => 'about-us-information',
            'attribute' => 'about_happy_customers',
            'field_name' => 'Happy Customers Number',
            'field_type' => 'text',
            'value' => 0,
        ]);

        Setting::updateOrCreate([
            'attribute' => 'about_team_members',
        ], [
            'setting_group_slug' => 'about-us-information',
            'attribute' => 'about_team_members',
            'field_name' => 'Team Members Number',
            'field_type' => 'text',
            'value' => 0,
        ]);

        Setting::updateOrCreate([
            'attribute' => 'about_award',
        ], [
            'setting_group_slug' => 'about-us-information',
            'attribute' => 'about_award',
            'field_name' => 'Awards Number',
            'field_type' => 'text',
            'value' => 0,
        ]);

    }
}
