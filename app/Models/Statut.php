<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OzdemirBurak\Iris\Color\Hex;

class Statut extends Model
{
    use HasFactory;

    protected $table = 'status';

    protected $fillable = [
        'name',
        'color',
    ];

    /**
     * Plants
     */
    public function plants()
    {
        return $this->hasMany(Plant::class);
    }

    public function rgb()
    {
        $color = new Hex($this->color);

        return $color->toRgb();
    }

    /**
     * Get plant statut for update
     */
    public static function getStatut($plant)
    {
        $current = $plant->currentChecklist();

        if (! ($firstStage = $plant->firstStage()) || ($current && $firstStage->checklist && $current->id === $firstStage->checklist->id && $current->isStarted($plant)) || ! ($item = $plant->currentItem())) {
            $statut = Statut::whereRaw('LOWER(name) = ?', ['new'])->first();
        } elseif (($last = $plant->lastChecklist()) && $current && $last->id === $current->id && $last->isCompleted($plant)) {
            $statut = Statut::whereRaw('LOWER(name) = ?', ['ready'])->first();
        } else {
            $statut = Statut::where('id', $item->statut_id)->first();
        }

        return $statut;
    }
}
