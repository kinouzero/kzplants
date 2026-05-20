<?php

namespace Database\Seeders;

use App\Models\Preference;
use Illuminate\Database\Seeder;

class PreferenceSeeder extends Seeder
{
    public function run(): void
    {
        Preference::firstOrCreate(
            ['key' => 'lang'],
            ['name' => 'Language', 'type' => 'checklist', 'options' => json_encode(['props' => ['en' => 'English', 'fr' => 'French']])]
        );
        Preference::firstOrCreate(
            ['key' => 'theme'],
            ['name' => 'Dark mode', 'type' => 'checklist', 'options' => json_encode(['props' => ['dark' => 'dark', 'light' => 'light']])]
        );
        Preference::firstOrCreate(
            ['key' => 'table-length'],
            ['name' => 'Table length', 'type' => 'checklist', 'options' => json_encode(['props' => [10 => 10, 25 => 25, 50 => 50, 100 => 100]])]
        );
        Preference::firstOrCreate(['key' => 'timezone'], ['name' => 'Timezone', 'type' => 'text']);
        Preference::firstOrCreate(['key' => 'flush'], ['name' => 'Flush weeks', 'type' => 'number']);
        Preference::firstOrCreate(['key' => 'interval-watering-chemical'], ['name' => 'Interval between chemical', 'type' => 'number']);
        Preference::firstOrCreate(['key' => 'interval-watering-water'], ['name' => 'Interval between water', 'type' => 'number']);
    }
}
