<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@gmail.com',
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
                'status' => 1,
                'is_admin' => 1,
            ]
        );

        // Create a default regular user
        User::updateOrCreate(
            [
                'email' => 'user@example.com',
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'password' => bcrypt('password'),
                'status' => 1,
                'is_admin' => 0,
            ]
        );

        $this->call([
            SettingSeeder::class,
        ]);
    }
}
