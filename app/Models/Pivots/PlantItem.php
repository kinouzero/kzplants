<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PlantItem extends Pivot
{
    protected $table = 'plant_items';

    protected $casts = [
        'due' => 'datetime',
        'checked' => 'datetime',
        'flush' => 'boolean',
    ];
}
