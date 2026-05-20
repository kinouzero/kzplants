<?php

namespace Database\Seeders;

use App\Models\Statut;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'New', 'color' => '#6c757d'],
            ['name' => 'Vegetative', 'color' => '#198754'],
            ['name' => 'Flowering', 'color' => '#fd7e14'],
            ['name' => 'Ready', 'color' => '#0d6efd'],
        ];

        foreach ($rows as $row) {
            Statut::firstOrCreate([
                'name' => $row['name'],
            ], [
                'color' => $row['color'],
            ]);
        }
    }
}
