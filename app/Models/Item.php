<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model {
  use HasFactory;

  protected $table = 'items';

  protected $fillable = [
    'name',
    'checklist_id',
    'parent_id'
  ];

  /**
   * Search
   */
  public function search($search, $order = [], $limit = 25) {
    $query = Item::query();

    // Where
    $query->where('items.name', 'ilike', '%' . $search . '%');

    // Order
    $query->orderBy($order['by'] ?: 'items.name', $order['dir'] ?: 'desc');

    // Limit
    $query->limit($limit);

    return $query->get();
  }

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
   * Template timeline
   */
  public function templateTimeline($content = null) {
    return view('layouts.timeline.item', ['info' => null, 'content' => $content, 'class' => 'period']);
  }
}
