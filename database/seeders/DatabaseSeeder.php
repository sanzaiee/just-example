<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::updateOrCreate([
            'slug' => 'admin',
        ],[
            'name' => 'Admin',
            'slug' => 'admin',
        ]);

        User::updateOrCreate(
            [
                'email' => 'admin@gmail.com'
            ],
            [
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'status' => 1,
            'is_admin' => 1
            ]
        );

        $this->call([
            SettingSeeder::class,
            AboutUsSeeder::class,
            ContactUsSeeder::class,
            BannerSeeder::class,
        ]);
    }
}
