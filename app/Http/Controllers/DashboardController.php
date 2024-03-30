<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\Statut;
use App\Models\Strain;
use App\Models\Watering;

class DashboardController extends Controller
{
    // Views
    public function index()
    {
        $plants  = Plant::where('created_by', auth()->user()->id)->get();
        $strains = Strain::all();
        $status  = Statut::all();
        $style   = !$plants->isEmpty() ? sprintf('<style>%s</style>', implode(' ', [Plant::tagsStyle($plants), Plant::propertiesStyle($plants)])) : '';
        return view('dashboard', compact('plants', 'strains', 'status', 'style'));
    }

    public function getStatusChart()
    {
        $data   = [];
        $status = Statut::all();
        foreach ($status as $statut) {
            $data[] = [
                'name'  => $statut->name,
                'count' => $statut->plants->count(),
                'color' => $statut->color
            ];
        }
        return response()->json($data);
    }

    public function getWateringsChart()
    {
        $plants = Plant::all();

        $chem   = '#4e73df';
        $noChem = '#e74a3b';

        $countChem = $countNoChem = 0;
        foreach ($plants as $plant) {
            $countChem += $plant->waterWithChemical();
            $countNoChem += $plant->waterWithoutChemical();
        }

        return response()->json([
            ['name' => 'Chemical', 'count' => $countChem, 'color' => $chem],
            ['name' => 'No Chemical', 'count' => $countNoChem, 'color' => $noChem]
        ]);
    }
}
