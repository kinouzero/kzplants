<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model {

  protected $table = 'plants';

  protected $fillable = [
    'name',
    'strain_id',
    'created_by',
  ];

  /**
   * Dashboards
   */
  public function dashboards() {
    return $this->belongsToMany(Dashboard::class, 'dashboard_plants', 'plant_id', 'dashboard_id');
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
    return $this->belongsToMany(Picture::class, 'plant_pictures', 'plant_id', 'picture_id')->withPivot('default');
  }

  /**
   * Default picture
   */
  public function defaultPicture() {
    return $this->pictures()->wherePivot('default', true)->first() ?: $this->strain->defaultPicture();
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
    return $this->belongsToMany(Item::class, 'plant_items', 'plant_id', 'item_id')->withPivot('due', 'checked', 'flush');
  }

  /**
   * Current item
   */
  public function currentItem() {
    if (!$this->currentChecklist()) return null;
    return ($current = $this->currentChecklist()
      ->items()
      ->whereIn('parent_id', $this->items()->wherePivotNotNull('checked')->get()->pluck('id'))
      ->get()
      ->last()) ? $this->items() && $this->items()->where('item_id', $current->id)->first() : $this->currentChecklist()->items()->first();
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
   * Last watering chemical
   */
  public function lastWateringChemical() {
    return $this->waterings->first() ? $this->waterings->first()->chemical : false;
  }

  public function nextWateringChemical() {
    $next = !$this->lastWateringChemical();
    if (($current = $this->currentItem()) && $current->pivot && $current->pivot->flush) $next = false;
    return $next;
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

  /**
   * Comments
   */
  public function comments() {
    return $this->hasMany(Comment::class, 'plant_id');
  }

  /*************
   * Templates *
   *************/

  public function templateDetails() {
    return view('template.dashboard.status.details', ['checklist' => $this->currentChecklist(), 'item' => $this->currentItem()]);
  }

  /**
   * Template tags
   */
  public function templateTags() {
    return view('template.plant.tags', ['plant' => $this]);
  }

  /**
   * Template properties
   */
  public function templateProperties() {
    return view('template.plant.properties', ['plant' => $this]);
  }

  /**
   * Template tags styling attribute
   */
  public static function tagsStyle($plants) {
    $template = [];
    if ($plants) foreach ($plants as $plant) {
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
    if ($plants) foreach ($plants as $plant) {
      foreach ($plant->properties as $property) $template[$property->id] = sprintf('.property-%s { background-color:%s }', $property->id, $property->color);
      foreach ($plant->strain->properties as $property) $template[$property->id] = sprintf('.property-%s { background-color:%s }', $property->id, $property->color);
    }

    return implode(' ', $template);
  }

  /**
   * Template checklists
   */
  public function templateChecklists() {
    return view('template.plant.checklists', ['plant' => $this]);
  }

  public static function templateChecklist($plant, $checklist, $current) {
    return view('template.plant.checklist', ['template' => $checklist->template($plant, $current && $current->id == $checklist->id)]);
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
    $tz      = User::getUserTimezone(auth()->user());
    $created = \Carbon\Carbon::parse($this->created_at, $tz);
    $history[$created->timestamp] = view('template.timeline.item', ['info' => view('template.timeline.item.info', ['date' => $created]), 'content' => view('template.timeline.item.content', ['content' => 'Created']), 'class' => 'mb-0']);

    if ($this->waterings->count() > 0) foreach ($this->waterings as $watering) {
      $date = \Carbon\Carbon::parse($watering->created_at, $tz);
      $history[$date->timestamp] = $watering->templateTimeline(
        view('template.timeline.item.content', ['content' => sprintf('Water with%s chemical', $watering->chemical ? '' : 'out')]),
        view('template.timeline.item.info', ['date' => $date]),
      );
    }

    if ($this->comments->count() > 0) foreach ($this->comments as $comment) {
      $date = \Carbon\Carbon::parse($comment->created_at, $tz);
      $history[$date->timestamp] = $comment->templateTimeline(
        view('template.timeline.item.comment', ['comment' => $comment]),
        view('template.timeline.item.info', ['date' => $date]),
      );
    }

    if ($this->pictures->count() > 0) foreach ($this->pictures as $picture) {
      $date = \Carbon\Carbon::parse($picture->created_at, $tz);
      $history[$date->timestamp] = $picture->templateTimeline(
        view('template.timeline.item.picture', ['picture' => $picture]),
        view('template.timeline.item.info', ['date' => $date]),
      );
    }

    if ($this->checkedItems) foreach ($this->checkedItems as $item) {
      $date = \Carbon\Carbon::parse($item->pivot->checked, $tz);
      $history[$date->timestamp] = $item->templateTimeline(
        view('template.timeline.period.content', ['content' => sprintf('%s<br/><i class="fas fa-check text-success fa-2xs me-2"></i>%s', $item->statut->name, $item->name), 'date' => $date, 'next' => null]),
      );
    }

    krsort($history);

    array_unshift($history, view('template.timeline.item', ['info' => null, 'content' => view('template.timeline.period.content', ['content' => '<i class="fas fa-droplet fa-xs me-2"></i>Next watering', 'date' => null, 'next' => $this->nextWateringChemical() ? 'biohazard text-danger' : 'water text-primary']), 'class' => 'period']));

    return view('template.plant.timeline', ['history' => $history]);
  }
}
