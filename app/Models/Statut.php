<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use OzdemirBurak\Iris\Color\Hex;

class Statut extends Model
{
    use HasFactory;

    protected $table = "status";

    protected $fillable = [
        'name',
        'color'
    ];

    /**
     * Search
     */
    public function search($search, $order = [], $limit = 25)
    {
        $query = Property::query();

        // Where
        $query->where('status.name', 'ilike', '%' . $search . '%');

        // Order
        $query->orderBy($order['by'] ?: 'status.name', $order['dir'] ?: 'desc');

        // Limit
        $query->limit($limit);

        return $query->get();
    }

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

        if (!($first = $plant->firstChecklist()) || ($first->id === $current->id && $first->isStarted($plant)) || !($item = $plant->currentItem())) $statut = Statut::where('name', 'ilike', 'new')->first();
        elseif (($last = $plant->lastChecklist())->id === $current->id && $last->isCompleted()) $statut = Statut::where('id', 'ilike', 'ready')->first();
        else $statut = Statut::where('id', $item->statut_id)->first();

        return $statut;
    }
}
