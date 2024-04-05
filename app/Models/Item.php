<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model {

  protected $table = 'items';

  protected $fillable = [
    'name',
    'checklist_id',
    'parent_id'
  ];

  /**
   * Checklist
   */
  public function checklist() {
    return $this->belongsTo(Checklist::class, 'checklist_id');
  }

  /**
   * Parent
   */
  public function parent() {
    return $this->belongsTo(Item::class, 'parent_id');
  }

  /**
   * Child
   */
  public function child() {
    return $this->hasOne(Item::class, 'parent_id');
  }

  /**
   * Statut
   */
  public function statut() {
    return $this->belongsTo(Statut::class, 'statut_id');
  }

  /**
   * Template timeline
   */
  public function templateTimeline($content = null) {
    return view('template.timeline.item', ['info' => null, 'content' => $content, 'class' => 'period']);
  }
}
