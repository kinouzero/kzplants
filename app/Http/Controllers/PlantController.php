<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Checklist;
use App\Models\Comment;
use App\Models\Dashboard;
use App\Models\Item;
use App\Models\Picture;
use App\Models\Plant;
use App\Models\Property;
use App\Models\Statut;
use App\Models\Strain;
use App\Models\Tag;
use App\Models\User;

class PlantController extends Controller {

  // Views
  public function index() {
    $plants  = Plant::all();
    $strains = Strain::all();
    $style   = !$plants->isEmpty() ? sprintf('<style>%s</style>', implode(' ', [Plant::tagsStyle($plants), Plant::propertiesStyle($plants)])) : '';

    return view('plant.index', compact('plants', 'strains', 'style'));
  }

  public function create() {
    $plant      = null;
    $dashboards = Dashboard::all();
    $strains    = Strain::all();
    $tags       = Tag::all();
    $properties = Property::all();

    $title = 'Create new plant';

    $options_dashboards = $options_strains = $option_tags = $option_properties = [];
    foreach ($dashboards as $_dashboard) $options_dashboards[] = view('template.form.select.option', ['value' => $_dashboard->id, 'title' => $_dashboard->name, 'selected' => Dashboard::getCurrentDashboard()->id === $_dashboard->id]);
    foreach ($strains as $strain) $options_strains[] = view('template.form.select.option', ['value' => $strain->id, 'title' => $strain->name, 'selected' => false]);
    foreach ($tags as $tag) $option_tags[] = view('template.form.select.option', ['value' => $tag->id, 'title' => $tag->name, 'selected' => false]);
    foreach ($properties as $property) $option_properties[] = view('template.form.select.option', ['value' => $property->id, 'title' => $property->name, 'selected' => false]);

    $template_properties = [
      view('template.property.form.row', [
        'id'      => null,
        'value'   => null,
        'type'    => 'select',
        'key'     => 'properties',
        'label'   => 'Property',
        'options' => $option_properties
      ]),
      view('template.alert', ['color' => 'secondary', 'class' => 'mb-0', 'content' => 'No property yet'])
    ];

    return view('plant.edit', compact('plant', 'options_dashboards', 'options_strains', 'option_tags', 'template_properties', 'title'));
  }

  public function edit($id) {
    $plant      = Plant::findOrFail($id);
    $dashboards = Dashboard::all();
    $strains    = Strain::all();
    $tags       = Tag::all();
    $properties = Property::all();

    $title = sprintf('Edit plant: %s', $plant->name);

    $alert = null;
    $options_dashboards = $options_strains = $option_tags = $option_properties = $value_properties = [];
    foreach ($dashboards as $_dashboard) $options_dashboards[] = view('template.form.select.option', ['value' => $_dashboard->id, 'title' => $_dashboard->name, 'selected' => $plant->dashboards()->where('dashboard_id', $_dashboard->id)->exists()]);
    foreach ($strains as $strain) $options_strains[] = view('template.form.select.option', ['value' => $strain->id, 'title' => $strain->name, 'selected' => $plant->strain->id === $strain->id]);
    foreach ($tags as $tag) $option_tags[] = view('template.form.select.option', ['value' => $tag->id, 'title' => $tag->name, 'selected' => $plant->tags->contains('id', $tag->id)]);
    foreach ($properties as $property) {
      $option_properties['clone'][] = view('template.form.select.option', ['value' => $property->id, 'title' => $property->name, 'selected' => false]);
      $value_properties['clone'] = null;
      if ($plant && $plant->properties->count() > 0) foreach ($plant->properties as $plantProperty) {
        $option_properties[$plantProperty->pivot->property_id][] = view('template.form.select.option', ['value' => $property->id, 'title' => $property->name, 'selected' => $plantProperty->pivot->property_id === $property->id]);
        $value_properties[$plantProperty->pivot->property_id] = $plantProperty->pivot->value;
      }
      else $alert = view('template.alert', ['color' => 'secondary', 'class' => 'mb-0', 'content' => 'No property yet']);
    }

    $template_properties = [];
    foreach ($option_properties as $k => $options) $template_properties[] = view('template.row.form', [
      'id'      => $k === 'clone' ? null : Str::uuid(),
      'value'   => $value_properties[$k] ?: '',
      'type'    => 'select',
      'key'     => 'properties',
      'label'   => 'Property',
      'options' => $options,
    ]);

    if ($alert) $template_properties[] = $alert;

    return view('plant.edit', compact('plant', 'options_dashboards', 'options_strains', 'option_tags', 'template_properties', 'title'));
  }

  public function detail($id) {
    $plant = Plant::findOrFail($id);
    $style = !$plant ? sprintf('<style>%s</style>', implode(' ', [Plant::tagsStyle([$plant]), Plant::propertiesStyle([$plant])])) : '';

    return view('plant.detail', compact('plant', 'style'));
  }

  public function checklists($id) {
    $plant      = Plant::findOrFail($id);
    $checklists = Checklist::all();
    $initial    = $plant->checklists()->wherePivot('initial', true)->first();

    return view('plant.checklists', compact('plant', 'checklists', 'initial'));
  }

  public function pictures($id) {
    $plant = Plant::findOrFail($id);

    return view('plant.pictures', compact('plant'));
  }

  // Actions
  public function store(Request $request) {
    $plant = new Plant();

    $args = $request->all();

    $validatedData = $request->validate([
      'name'      => 'required|string',
      'strain_id' => 'required|integer'
    ]);

    $plant->name       = $validatedData['name'];
    $plant->strain_id  = $validatedData['strain_id'];
    $plant->created_by = auth()->user()->id;
    $plant->statut_id  = Statut::getStatut($plant)->id;
    $plant->save();

    // Dashboards
    $dashboards = isset($args['dashboards']) ? Dashboard::whereIn('id', $args['dashboards'])->get() : null;
    $plant->dashboards()->attach($dashboards ? $dashboards->pluck('id')->toArray() : []);

    // Tags
    $tags = isset($args['tags']) ? Tag::whereIn('id', $args['tags'])->get() : null;
    $plant->tags()->attach($tags ? $tags->pluck('id')->toArray() : []);

    // Properties
    $propertyValue = [];
    if ($values = $args['values']) foreach ($values as $k => $v) if ($v) $propertyValue[] = [
      'plant_id'    => $plant->id,
      'property_id' => $args['properties'][$k],
      'value'       => $v
    ];
    $plant->properties()->attach($propertyValue);

    return back()->with('success', 'Plant created successfully.');
  }

  public function update(Request $request, $id) {
    $plant = Plant::findOrFail($id);
    $args  = $request->all();

    // Dashboards
    $dashboards = isset($args['dashboards']) ? Dashboard::whereIn('id', $args['dashboards'])->get() : null;
    $plant->dashboards()->sync($dashboards ? $dashboards->pluck('id')->toArray() : []);
    unset($args['dashboards']);

    // Tags
    $tags = isset($args['tags']) ? Tag::whereIn('id', $args['tags'])->get() : null;
    $plant->tags()->sync($tags ? $tags->pluck('id')->toArray() : []);
    unset($args['tags']);

    // Properties
    $propertyValue = [];
    if ($values = $args['values']) foreach ($values as $k => $v) if ($v) $propertyValue[] = [
      'plant_id'    => $plant->id,
      'property_id' => $args['properties'][$k],
      'value'       => $v
    ];
    $plant->properties()->sync($propertyValue);
    unset($args['values'], $args['properties']);

    $plant->update($args);

    return back()->with('success', 'Plant updated successfully.');
  }

  public function destroy($id) {
    $plant = Plant::findOrFail($id);
    $plant->delete();

    return back()->with('success', 'Plant deleted successfully.');
  }

  public function addToPlant(Request $request, $id, $objectType, $object_id) {
    $plant = Plant::findOrFail($id);
    if ($objectType === 'checklist' && $checklist = Checklist::findOrFail($object_id)) {
      $plant->checklists()->attach($checklist, ['initial' => false]);

      return back()->with('success', 'Checklist added successfully.');
    }
    if ($objectType === 'initial' && $checklist = Checklist::findOrFail($object_id)) {
      $plant->checklists()->updateExistingPivot($plant->checklists()->pluck('id'), ['initial' => false]);
      $plant->checklists()->updateExistingPivot($checklist->id, ['initial' => true]);

      return back()->with('success', 'First checklist added successfully.');
    }
    if ($objectType === 'flush' && $item = Item::findOrFail($object_id)) {
      $plant->items()->syncWithoutDetaching([$item->id => ['flush' => true]]);

      return back()->with('success', 'Flush added successfully.');
    }
    if ($objectType === 'default-picture' && $picture = Picture::findOrFail($object_id)) {
      $plant->pictures()->updateExistingPivot($plant->pictures()->pluck('id'), ['default' => false]);
      $plant->pictures()->updateExistingPivot($picture->id, ['default' => true]);

      return back()->with('success', 'Default picture added successfully.');
    }
  }

  public function removeFromPlant(Request $request, $id, $objectType, $object_id) {
    $plant = Plant::findOrFail($id);
    if ($objectType === 'checklist' && $checklist = Checklist::findOrFail($object_id)) {
      $plant->checklists()->detach($checklist);

      return back()->with('success', 'Checklist removed successfully.');
    }
    if ($objectType === 'initial' && $checklist = Checklist::findOrFail($object_id)) {
      $plant->checklists()->updateExistingPivot($checklist->id, ['initial' => false]);

      return back()->with('success', 'First checklist removed successfully.');
    }
    if ($objectType === 'flush' && $item = Item::findOrFail($object_id)) {
      $plant->items()->syncWithoutDetaching([$item->id => ['flush' => false]]);

      return back()->with('success', 'Flush added successfully.');
    }
    if ($objectType === 'default-picture' && $picture = Picture::findOrFail($object_id)) {
      $plant->pictures()->updateExistingPivot($picture->id, ['default' => false]);

      return back()->with('success', 'Default picture removed successfully.');
    }
  }

  public function itemDueSave(Request $request, $id) {
    $plant = Plant::findOrFail($id);
    $plant->items()->syncWithoutDetaching([$request->item_id => ['due' => $request->due]]);

    return back()->with('success', 'Due date saved successfully.');
  }

  public function itemDueRemove(Request $request, $id) {
    $plant = Plant::findOrFail($id);
    $plant->items()->syncWithoutDetaching([$request->item_id => ['due' => null]]);

    return back()->with('success', 'Due date removed successfully.');
  }

  public function itemToggle(Request $request, $id) {
    $plant = Plant::findOrFail($id);
    $plant->items()->syncWithoutDetaching([$request->item_id => ['checked' => $request->checked === 'false' ? now(User::getUserTimezone(auth()->user())) : null]]);

    // Update statut
    $plant->statut_id = Statut::getStatut($plant)->id;
    $plant->save();

    return back()->with('success', 'Checklist item updated successfully.');
  }

  public function water(Request $request, $id) {
    $plant = Plant::findOrFail($id);
    $plant->waterings()->create(['chemical' => false]);

    return back()->with('success', 'Watering without chemical done successfully.');
  }

  public function waterChemical(Request $request, $id) {
    $plant = Plant::findOrFail($id);
    $plant->waterings()->create(['chemical' => true]);

    return back()->with('success', 'Watering with chemical done successfully.');
  }

  public function commentAdd(Request $request, $id) {
    $plant = Plant::findOrFail($id);

    $comment            = new Comment();
    $comment->plant_id  = $plant->id;
    $comment->author_id = auth()->user()->id;
    $comment->value     = $request->comment;
    $comment->save();

    return back()->with('success', 'Comment added successfully.');
  }

  public function commentEdit(Request $request, $id) {
    $plant          = Plant::findOrFail($id);
    $comment        = $plant->comments()->findOrFail($request->comment_id);
    $comment->value = $request->comment;
    $comment->save();

    return back()->with('success', 'Comment edited successfully.');
  }

  public function commentRemove(Request $request, $id) {
    $plant   = Plant::findOrFail($id);
    $comment = $plant->comments()->findOrFail($request->comment_id);
    $comment->delete();

    return back()->with('success', 'Comment removed successfully.');
  }
}
