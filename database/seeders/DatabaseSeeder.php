<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 🧩 Order matters — users first, then related models
        $this->call([
            UserSeeder::class,
            PostSeeder::class,
            HelpOfferSeeder::class
        ]);
    }
}
