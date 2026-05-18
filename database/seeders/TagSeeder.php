<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tag::updateOrCreate([
            'slug' => 'nicotine-pouches',
        ], [
            'slug' => 'nicotine-pouches',
            'name' => 'Nicotine Pouches',
            'description' => '',
        ]);

        Tag::updateOrCreate([
            'slug' => 'caffeine-pouches',
        ], [
            'slug' => 'caffeine-pouches',
            'name' => 'Caffeine Pouches',
            'description' => '',
        ]);

        Tag::updateOrCreate([
            'slug' => 'vapes',
        ], [
            'slug' => 'vapes',
            'name' => 'Vapes',
            'description' => '',
        ]);

        Tag::updateOrCreate([
            'slug' => 'cigarettes-cigars',
        ], [
            'slug' => 'cigarettes-cigars',
            'name' => 'Cigarettes & Cigars',
            'description' => '',
        ]);
    }
}
