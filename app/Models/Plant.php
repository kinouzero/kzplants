<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class Plant extends Model {
  use HasFactory;

  protected $table = 'plants';

  protected $fillable = [
    'name',
    'strain_id',
    'created_by',
  ];

  /**
   * Search
   */
  public function search($search, $order = [], $limit = 25) {
    return Plant::query()
      ->join('strains', 'strains.id', '=', 'plants.tag_id')
      ->join('tags', '$tags.id', '=', 'plants.tag_id')
      ->join('plant_properties', 'plant_properties.plant_id', '=', 'plants.id')
      ->join('properties', 'properties.id', '=', 'plant_properties.property_id')
      ->join('plant_watering', 'plant_watering.plant_id', '=', 'plants.id')
      ->where(function ($query) use ($search) {
        $query->where('plants.name', 'ilike', '%' . $search . '%')
          ->orWhere('plants.status', 'ilike', '%' . $search . '%')
          ->orWhere('tags.name', 'ilike', '%' . $search . '%')
          ->orWhere('plant_properties.value', 'ilike', '%' . $search . '%')
          ->orWhere('properties.name', 'ilike', '%' . $search . '%')
          ->orWhere('plant_watering.date', 'ilike', '%' . $search . '%');
      })
      ->orderBy($order['by'] ?: 'plants.name', $order['dir'] ?: 'desc')
      ->limit($limit)
      ->get();
  }

  /**
   * Strain
   */
  public function strain() {
    return $this->belongsTo(Strain::class, 'strain_id');
  }

  /**
   * Statut
   */
  public function statut() {
    return $this->belongsTo(Statut::class, 'statut_id');
  }

  /**
   * Pictures
   */
  public function pictures() {
    return $this->belongsToMany(Picture::class, 'plant_pictures', 'plant_id', 'picture_id');
  }

  /**
   * Properties
   */
  public function properties() {
    return $this->belongsToMany(Property::class, 'plant_properties', 'plant_id', 'property_id')->withPivot('value');
  }

  /**
   * Tags
   */
  public function tags() {
    return $this->belongsToMany(Tag::class, 'plant_tags', 'plant_id', 'tag_id');
  }

  /**
   * Checklists
   */
  public function checklists() {
    return $this->belongsToMany(Checklist::class, 'plant_checklists', 'plant_id', 'checklist_id')->withPivot('initial');
  }

  /**
   * Initial checklist
   */
  public function firstChecklist() {
    return $this->checklists()->wherePivot('initial', true)->first();
  }

  /**
   * Current checklist
   */
  public function currentChecklist() {
    foreach ($this->checklists as $checklist) if (!$checklist->isCompleted($this)) return $checklist;
    return null;
  }

  /**
   * Last checklist
   */
  public function lastChecklist() {
    return collect($this->firstChecklist()->tree())->last();
  }

  /**
   * Checklist items
   */
  public function items() {
    return $this->belongsToMany(Item::class, 'plant_items', 'plant_id', 'item_id')->withPivot('due', 'checked');
  }

  /**
   * Current item
   */
  public function currentItem() {
    return $this
      ->currentChecklist()
      ->items()
      ->whereIn('parent_id', $this->items()->wherePivotNotNull('checked')->get()->pluck('id'))
      ->first() ?: $this->currentChecklist()->items()->first();
  }

  /**
   * Checked items
   */
  public function checkedItems() {
    return $this->items()->wherePivotNotNull('checked');
  }

  /**
   * Waterings
   */
  public function waterings() {
    return $this->hasMany(Watering::class, 'plant_id')->orderBy('created_at', 'desc');
  }

  /**
   * Count water with chemical
   */
  public function waterWithChemical() {
    return $this->waterings()->where('chemical', true)->count();
  }

  /**
   * Count water without chemical
   */
  public function waterWithoutChemical() {
    return $this->waterings()->where('chemical', false)->count();
  }

  /*************
   * Templates *
   *************/

  public function templateDetails() {
    return view('layouts.dashboard.status.details', ['checklist' => $this->currentChecklist(), 'item' => $this->currentItem(), 'chemical' => ($last = $this->waterings->last()) && $last->chemical]);
  }

  /**
   * Template tags
   */
  public function templateTags() {
    return view('layouts.plant.tags', ['plant' => $this]);
  }

  /**
   * Template properties
   */
  public function templateProperties() {
    return view('layouts.plant.properties', ['plant' => $this]);
  }

  /**
   * Template tags styling attribute
   */
  public static function tagsStyle($plants) {
    $template = [];
    if (!$plants->isEmpty()) foreach ($plants as $plant) {
      foreach ($plant->tags as $tag) $template[$tag->id] = sprintf('.tag-%s { background-color:%s }', $tag->id, $tag->color);
      foreach ($plant->strain->tags as $tag) $template[$tag->id] = sprintf('.tag-%s { background-color:%s }', $tag->id, $tag->color);
    }

    return implode(' ', $template);
  }

  /**
   * Template properties styling attribute
   */
  public static function propertiesStyle($plants) {
    $template = [];
    if (!$plants->isEmpty()) foreach ($plants as $plant) {
      foreach ($plant->properties as $property) $template[$property->id] = sprintf('.property-%s { background-color:%s }', $property->id, $property->color);
      foreach ($plant->strain->properties as $property) $template[$property->id] = sprintf('.property-%s { background-color:%s }', $property->id, $property->color);
    }

    return implode(' ', $template);
  }

  /**
   * Template checklists
   */
  public function templateChecklists() {
    return view('layouts.plant.checklists', ['plant' => $this]);
  }

  public static function templateChecklist($plant, $checklist, $current) {
    return view('layouts.plant.checklist', ['template' => $checklist->template($plant, $current && $current->id == $checklist->id)]);
  }

  /**
   * Template checklist tree
   */
  public static function templateChecklistTree($plant, $checklist = null, $current = null) {
    $template = [];
    if (!$checklist) $checklist = $plant->firstChecklist();
    if (!$current) $current = $plant->currentChecklist();

    $template[] = self::templateChecklist($plant, $checklist, $current);
    foreach ($checklist->children()->whereIn('checklist_id', $plant->checklists()->pluck('id'))->get() as $child) $template[] = self::templateChecklistTree($plant, $child, $current);

    return implode('', $template);
  }

  /**
   * Template timeline
   */
  public function templateTimeline() {
    $history = [];
    $tz      = User::getTimezone(auth()->user());
    $created = \Carbon\Carbon::parse($this->created_at, $tz);
    $history[$created->timestamp] = view('layouts.timeline.item', ['info' => view('layouts.timeline.item.info', ['date' => $created]), 'content' => view('layouts.timeline.item.content', ['content' => 'Created']), 'class' => 'mb-0']);

    if ($this->waterings->count() > 0) foreach ($this->waterings as $watering) {
      $date = \Carbon\Carbon::parse($watering->created_at, $tz);
      $history[$date->timestamp] = $watering->templateTimeline(
        view('layouts.timeline.item.content', ['content' => sprintf('Water with%s chemical', $watering->chemical ? '' : 'out')]),
        view('layouts.timeline.item.info', ['date' => $date]),
      );
    }

    if ($this->checkedItems) foreach ($this->checkedItems as $item) {
      $date = \Carbon\Carbon::parse($item->pivot->checked, $tz);
      $history[$date->timestamp] = $item->templateTimeline(
        view('layouts.timeline.period.content', ['content' => sprintf('<i class="fas fa-check text-success me-2"></i>%s', $item->name), 'date' => $date, 'next' => null]),
      );
    }

    krsort($history);

    array_unshift($history, view('layouts.timeline.item', ['info' => null, 'content' => view('layouts.timeline.period.content', ['content' => 'Next watering', 'date' => null, 'next' => !($last = $this->waterings->first()) || !$last->chemical ? 'biohazard text-danger' : 'water text-primary']), 'class' => 'period']));

    return view('layouts.plant.timeline', ['history' => $history]);
  }
}
