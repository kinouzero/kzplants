<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Checklist extends Model {
  use HasFactory;

  protected $table = 'checklists';

  protected $fillable = [
    'name',
    'icon'
  ];

  /**
   * Search
   */
  public function search($search, $order = [], $limit = 25) {
    $query = Checklist::query();

    // Join
    $query->join('items', 'items.checklist_id', '=', 'checklists.id');
    $query->join('items', 'items.item_id', '=', 'items.id');

    // Where
    $query->where('checklists.name', 'ilike', '%' . $search . '%');
    $query->where('items.name', 'ilike', '%' . $search . '%');

    // Order
    $query->orderBy($order['by'] ?: 'lists.name', $order['dir'] ?: 'desc');

    // Limit
    $query->limit($limit);

    return $query->get();
  }

  /**
   * Parents
   */
  public function parents() {
    return $this->belongsToMany(Checklist::class, 'checklist_parents', 'checklist_id', 'parent_id');
  }

  /**
   * Children
   */
  public function children() {
    return $this->belongsToMany(Checklist::class, 'checklist_parents', 'parent_id', 'checklist_id');
  }

  public function tree() {

    $tree = [];

    $this->children()->each(function ($child) use (&$tree) {
      $tree[] = $child;
      $tree = array_merge($tree, $child->tree());
    });

    return $tree;
  }

  /**
   * Items
   */
  public function items() {
    return $this->hasMany(Item::class, 'checklist_id', 'id');
  }

  /**
   * First item
   */
  public function firstItem() {
    return $this->items()->where('parent_id', null)->first();
  }

  /**
   * Checklist is started
   */
  public function isStarted($plant) {
    return DB::table('plant_items')->select()->where('plant_id', $plant->id)->whereIn('item_id', $this->items()->pluck('id'))->whereNotNull('checked')->count() === 0;
  }

  /**
   * Checklist is completed
   */
  public function isCompleted($plant) {
    return DB::table('plant_items')->select()->where('plant_id', $plant->id)->whereIn('item_id', $this->items()->pluck('id'))->whereNotNull('checked')->count() === $this->items()->count();
  }

  /*************
   * Templates *
   *************/

  /**
   * Template checklist
   */
  public function template($plant = null, $active = false) {
    return view('layouts.checklist.card', ['checklist' => $this, 'plant' => $plant, 'active' => $active, 'completed' => $this->isCompleted($plant)]);
  }

  /**
   * Template item line
   */
  private static function templateItemLine($item, $plant, $page, $current) {
    $route = sprintf('layouts.checklist.item.line.%s', $page);
    $tz    = User::getTimezone(auth()->user());

    $plantItem = $plant->items()->where('item_id', $item->id)->first();

    $now = Carbon::now($tz);
    $due =
      $plantItem && $plantItem->pivot->due
      ? Carbon::parse($plantItem->pivot->due, $tz)
      : null;
    $checked =
      $plantItem && $plantItem->pivot->checked
      ? Carbon::parse($plantItem->pivot->checked, $tz)
      : null;

    $dayBeforeDue = $due ? Carbon::parse($due, $tz)->subDay() : null;
    $restMoreThan1Day = $due ? $now->lessThan($dayBeforeDue) : null;
    $lessThan24h = $due ? $now->diffInHours($due, false) < 24 && $now->diffInHours($due, false) > 0 : null;

    return view($route, $page === 'detail' ? ['item' => $item, 'plant' => $plant, 'checklist' => $item->checklist, 'current' => $current, 'hours' => ['due' => $due, 'checked' => $checked], 'conditions' => ['restMoreThan1Day' => $restMoreThan1Day, 'lessThan24h' => $lessThan24h]] : ['item' => $item]);
  }

  /**
   * Template items tree
   */
  public static function templateItemsTree($plant, $checklist, $page, $current = null, $item = null) {
    $template = [];
    if (!$item) $item = $checklist->firstItem();
    if (!$current) $current = $plant->currentItem();


    $template[] = self::templateItemLine($item, $plant, $page, $current);
    if ($child = $item->child) $template[] = self::templateItemsTree($plant, $checklist, $page, $current, $child);

    return implode('', $template);
  }
}
