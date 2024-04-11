<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Watering extends Model {

  protected $table = 'plant_waterings';

  protected $fillable = [
    'plant_id',
    'date',
    'chemical',
  ];

  /**
   * Plant
   */
  public function plant() {
    return $this->belongsTo(Plant::class, 'plant_id');
  }

  /**
   * Template timeline
   */
  public function templateTimeline($content = null, $info = null) {
    return view('template.timeline.item', ['info' => $info, 'content' => $content, 'class' => $this->chemical ? 'w-chem' : 'wo-chem']);
  }
}
