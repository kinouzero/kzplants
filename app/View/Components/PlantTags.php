<?php

namespace App\View\Components;

use App\Models\Plant;
use Illuminate\View\Component;

class PlantTags extends Component
{
    public Plant $plant;

    public function __construct(Plant $plant)
    {
        $this->plant = $plant;
    }

    public function render()
    {
        return view('components.plant-tags');
    }
}
