<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'THC'],
            ['name' => 'CBD'],
            ['name' => 'Yield'],
            ['name' => 'Height'],
        ];

        foreach ($rows as $row) {
            Property::firstOrCreate([
                'name' => $row['name'],
            ]);
        }
    }
}
