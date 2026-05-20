<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'Indoor', 'color' => '#0d6efd'],
            ['name' => 'Outdoor', 'color' => '#198754'],
            ['name' => 'Auto', 'color' => '#6f42c1'],
            ['name' => 'Photo', 'color' => '#fd7e14'],
        ];

        foreach ($rows as $row) {
            Tag::firstOrCreate([
                'name' => $row['name'],
            ], [
                'color' => $row['color'],
            ]);
        }
    }
}
