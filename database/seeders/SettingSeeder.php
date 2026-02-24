<?php

namespace Database\Seeders;

use App\Models\Setting;
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
            'value' => 'BJM'
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

        // Uber direct return label instructions
        Setting::updateOrCreate([
            'attribute' => 'uber_direct_return_label_instructions',
        ], [
            'setting_group_slug' => 'general-information',
            'attribute' => 'uber_direct_return_label_instructions',
            'field_name' => 'Uber Direct Return Label Instructions',
            'field_type' => 'text',
            'value' => 'Return package to Unit #101. Leave inside the black storage bin and send photo image proof of return to: 647-556-6452. Thank you'
        ]);

        // Uber direct create shipping (Pickup instructions)
        Setting::updateOrCreate([
            'attribute' => 'uber_direct_pickup_instructions',
        ], [
            'setting_group_slug' => 'general-information',
            'attribute' => 'uber_direct_pickup_instructions',
            'field_name' => 'Uber Direct Pickup Instructions',
            'field_type' => 'text',
            'value' => 'PLEASE READ! Pickup at unit #101, which is on the "OUTSIDE" towards the East-side of the Condo Hayden Street entrance. Go through the black gate and ring the doorbell OR call 647-556-6452.'
        ]);

        // Store Pickup Instructions
        Setting::updateOrCreate([
            'attribute' => 'store_pickup_instructions',
        ], [
            'setting_group_slug' => 'general-information',
            'attribute' => 'store_pickup_instructions',
            'field_name' => 'Store Pickup Instructions',
            'field_type' => 'text',
            'value' => 'PLEASE READ! Pickup at unit #101, which is on the "OUTSIDE" towards the East-side of the Condo Hayden Street entrance. Go through the black gate and ring the doorbell OR call 647-556-6452. Collect at your box'
        ]);
    }
}
