<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $table = 'stages';

    protected $fillable = [
        'name',
        'checklist_id',
        'order',
        'interval_stage_days',
        'interval_item_days',
    ];

    public function checklist()
    {
        return $this->belongsTo(Checklist::class, 'checklist_id');
    }

    public function plants()
    {
        return $this->belongsToMany(Plant::class, 'plant_stages', 'stage_id', 'plant_id')->withPivot('initial');
    }
}
