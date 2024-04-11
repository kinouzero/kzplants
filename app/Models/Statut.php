<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
