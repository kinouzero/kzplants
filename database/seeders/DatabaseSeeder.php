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
        $this->call([
            StatusSeeder::class,
            TagSeeder::class,
            PreferenceSeeder::class,
            PropertySeeder::class,
            ChecklistSeeder::class,
        ]);

        if (env('SEED_DEMO', false)) {
            $this->call(DemoSeeder::class);
        }
    }
}
