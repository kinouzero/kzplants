<?php

namespace App\View\Components;

use App\Models\Strain;
use Illuminate\View\Component;

class StrainProperties extends Component
{
    public Strain $strain;

    public function __construct(Strain $strain)
    {
        $this->strain = $strain;
    }

    public function render()
    {
        return view('components.strain-properties');
    }
}
