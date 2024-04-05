<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Checklist extends Model {

  protected $table = 'checklists';

  protected $fillable = [
    'name',
    'icon'
  ];

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
    return view('template.checklist.card', ['checklist' => $this, 'plant' => $plant, 'active' => $active, 'completed' => $this->isCompleted($plant)]);
  }

  /**
   * Template item line
   */
  private static function templateItemLine($item, $plant, $page, $current) {
    $route = sprintf('template.checklist.item.line.%s', $page);
    $tz    = User::getUserTimezone(auth()->user());

    $plantItem = $plant->items()->where('item_id', $item->id)->first();

    $now = Carbon::now($tz);
    $flush = $plantItem && $plantItem->pivot->flush ? true : false;
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

    return view($route, $page === 'detail' ? ['item' => $item, 'plant' => $plant, 'checklist' => $item->checklist, 'current' => $current && ($current->id === $item->id), 'hours' => ['due' => $due, 'checked' => $checked], 'conditions' => ['restMoreThan1Day' => $restMoreThan1Day, 'lessThan24h' => $lessThan24h], 'flush' => $flush] : ['item' => $item, 'plant' => $plant, 'flush' => $flush]);
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
