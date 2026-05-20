<?php

namespace Database\Seeders;

use App\Models\Checklist;
use App\Models\Item;
use App\Models\Stage;
use App\Models\Statut;
use Illuminate\Database\Seeder;

class ChecklistSeeder extends Seeder
{
    public function run(): void
    {
        $statusNew = Statut::whereRaw('LOWER(name) = ?', ['new'])->first();
        $statusVeg = Statut::whereRaw('LOWER(name) = ?', ['vegetative'])->first();
        $statusFlo = Statut::whereRaw('LOWER(name) = ?', ['flowering'])->first();
        $statusReady = Statut::whereRaw('LOWER(name) = ?', ['ready'])->first();

        $checklists = [
            [
                'name' => 'Germination',
                'icon' => 'fas fa-seedling',
                'items' => [
                    ['name' => 'Soak seeds', 'statut' => $statusNew],
                    ['name' => 'Plant in medium', 'statut' => $statusNew],
                ],
            ],
            [
                'name' => 'Vegetative',
                'icon' => 'fas fa-leaf',
                'items' => [
                    ['name' => 'Start nutrients', 'statut' => $statusVeg],
                    ['name' => 'Training / LST', 'statut' => $statusVeg],
                ],
            ],
            [
                'name' => 'Flowering',
                'icon' => 'fas fa-spa',
                'items' => [
                    ['name' => 'Switch to bloom', 'statut' => $statusFlo],
                    ['name' => 'Defoliation', 'statut' => $statusFlo],
                ],
            ],
            [
                'name' => 'Harvest',
                'icon' => 'fas fa-trophy',
                'items' => [
                    ['name' => 'Flush', 'statut' => $statusReady],
                    ['name' => 'Harvest', 'statut' => $statusReady],
                ],
            ],
        ];

        $created = [];

        foreach ($checklists as $row) {
            $checklist = Checklist::firstOrCreate([
                'name' => $row['name'],
            ], [
                'icon' => $row['icon'],
            ]);

            $created[] = $checklist;

            foreach ($row['items'] as $item) {
                if (! $item['statut']) {
                    continue;
                }
                Item::firstOrCreate([
                    'name' => $item['name'],
                    'checklist_id' => $checklist->id,
                ], [
                    'statut_id' => $item['statut']->id,
                    'parent_id' => null,
                ]);
            }
        }

        foreach ($created as $index => $checklist) {
            Stage::firstOrCreate([
                'checklist_id' => $checklist->id,
            ], [
                'name' => $checklist->name,
                'order' => $index + 1,
                'interval_stage_days' => 0,
                'interval_item_days' => 7,
            ]);
        }

        for ($i = 0; $i < count($created) - 1; $i++) {
            $parent = $created[$i];
            $child = $created[$i + 1];
            if (! $parent->children()->where('checklist_id', $child->id)->exists()) {
                $parent->children()->attach($child->id);
            }
        }
    }
}
