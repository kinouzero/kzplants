<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PlantStage extends Pivot
{
    protected $table = 'plant_stages';

    protected $casts = [
        'initial' => 'boolean',
    ];
}
