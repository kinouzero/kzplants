<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use OzdemirBurak\Iris\Color\Hex;

class Statut extends Model {

  protected $table = "status";

  protected $fillable = [
    'name',
    'color'
  ];

  /**
   * Plants
   */
  public function plants() {
    return $this->hasMany(Plant::class);
  }

  public function rgb() {
    $color = new Hex($this->color);
    return $color->toRgb();
  }

  /**
   * Get plant statut for update
   */
  public static function getStatut($plant) {
    $current = $plant->currentChecklist();

    if (!($first = $plant->firstChecklist()) || ($first->id === $current->id && $first->isStarted($plant)) || !($item = $plant->currentItem())) $statut = Statut::where('name', 'ilike', 'new')->first();
    elseif (($last = $plant->lastChecklist())->id === $current->id && $last->isCompleted()) $statut = Statut::where('id', 'ilike', 'ready')->first();
    else $statut = Statut::where('id', $item->statut_id)->first();

    return $statut;
  }
}
