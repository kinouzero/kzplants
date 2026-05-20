<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantHistory extends Model
{
    protected $table = 'plant_history';

    protected $fillable = [
        'plant_id',
        'checklist_id',
        'by',
        'data',
    ];

    public static function record(Plant $plant, User $user, array $data, ?Checklist $checklist = null): void
    {
        $resolvedChecklist = $checklist ?: $plant->currentChecklist();
        if (! $resolvedChecklist) {
            return;
        }

        self::create([
            'plant_id' => $plant->id,
            'checklist_id' => $resolvedChecklist->id,
            'by' => $user->id,
            'data' => json_encode($data),
        ]);
    }
}
