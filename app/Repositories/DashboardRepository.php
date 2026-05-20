<?php

namespace App\Repositories;

use App\Models\Dashboard;
use App\Models\Statut;

class DashboardRepository
{
    public function statusCounts(?Dashboard $dashboard): array
    {
        $plants = $dashboard ? $dashboard->plants()->get() : collect();
        $status = Statut::all();
        $data = [];

        foreach ($status as $statut) {
            $data[$statut->id] = [
                'name' => $statut->name,
                'count' => 0,
                'color' => $statut->color,
            ];
        }

        foreach ($plants as $plant) {
            $data[$plant->statut->id] = [
                'name' => $plant->statut->name,
                'count' => ++$data[$plant->statut->id]['count'],
                'color' => $plant->statut->color,
            ];
        }

        return array_values($data);
    }

    public function wateringCounts(?Dashboard $dashboard): array
    {
        $plants = $dashboard ? $dashboard->plants()->get() : collect();

        $chem = '#e74a3b';
        $noChem = '#4e73df';

        $countChem = $countNoChem = 0;
        foreach ($plants as $plant) {
            $countChem += $plant->waterWithChemical();
            $countNoChem += $plant->waterWithoutChemical();
        }

        return [
            ['name' => 'Chemical', 'count' => $countChem, 'color' => $chem],
            ['name' => 'No Chemical', 'count' => $countNoChem, 'color' => $noChem],
        ];
    }
}
