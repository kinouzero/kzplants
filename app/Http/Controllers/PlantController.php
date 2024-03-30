<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Checklist;
use App\Models\Item;
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
    $plant   = null;
    $strains = Strain::all();
    $tags    = Tag::all();
    $properties = Property::all();
    return view('plant.create', compact('plant', 'strains', 'tags', 'properties'));
  }

  public function edit($id) {
    $plant   = Plant::findOrFail($id);
    $strains = Strain::all();
    $tags    = Tag::all();
    $properties = Property::all();
    return view('plant.edit', compact('plant', 'strains', 'tags', 'properties'));
  }

  public function detail($id) {
    $plant = Plant::findOrFail($id);
    $style = !$plant ? sprintf('<style>%s</style>', implode(' ', [Plant::tagsStyle([$plant]), Plant::propertiesStyle([$plant])])) : '';
    return view('plant.detail', compact('plant', 'style'));
  }

  public function addChecklist($id) {
    $plant      = Plant::findOrFail($id);
    $checklists = Checklist::all();
    $initial    = $plant->checklists()->wherePivot('initial', true)->first();
    return view('plant.add.list', compact('plant', 'checklists', 'initial'));
  }

  // Actions
  public function store(Request $request) {
    $plant             = new Plant();
    $args              = $request->all();
    $validatedData     = $request->validate([
      'name'      => 'required|string',
      'strain_id' => 'required|integer'
    ]);
    $plant->name       = $validatedData['name'];
    $plant->strain_id  = $validatedData['strain_id'];
    $plant->created_by = auth()->user()->id;
    $plant->statut_id  = Statut::getStatut($plant);
    $plant->save();

    // Tags
    $tags = isset($args['tags']) ? Tag::whereIn('id', $args['tags'])->get() : null;
    if ($tags && $tags->count() > 0) $plant->tags()->sync($tags->pluck('id')->toArray());

    // Properties
    $propertyValue = [];
    if ($values = $args['values']) foreach ($values as $k => $v) if ($v) $propertyValue[] = [
      'plant_id'    => $plant->id,
      'property_id' => $args['properties'][$k],
      'value'       => $v
    ];
    if ($propertyValue) $plant->properties()->sync($propertyValue);

    return redirect()->route('plant.index')->with('success', 'Plant created successfully.');
  }

  public function update(Request $request, $id) {
    $plant = Plant::findOrFail($id);
    $args  = $request->all();

    // Tags
    $tags = isset($args['tags']) ? Tag::whereIn('id', $args['tags'])->get() : null;
    $plant->tags()->detach();
    if ($tags && $tags->count() > 0) $plant->tags()->sync($tags->pluck('id')->toArray());
    unset($args['tags']);

    // Properties
    $propertyValue = [];
    if ($values = $args['values']) foreach ($values as $k => $v) if ($v) $propertyValue[] = [
      'plant_id'    => $plant->id,
      'property_id' => $args['properties'][$k],
      'value'       => $v
    ];
    $plant->properties()->detach();
    if ($propertyValue) $plant->properties()->sync($propertyValue);
    unset($args['values'], $args['properties']);

    $plant->update($args);
    return redirect()->route('plant.index')->with('success', 'Plant updated successfully.');
  }

  public function destroy($id) {
    $plant = Plant::findOrFail($id);
    $plant->delete();
    return redirect()->route('plant.index')->with('success', 'Plant deleted successfully.');
  }

  public function addToPlant(Request $request, $id, $objectType, $object_id) {
    $plant = Plant::findOrFail($id);
    if ($objectType === 'checklist' && $checklist = Checklist::findOrFail($object_id)) {
      $plant->checklists()->attach($checklist, ['initial' => false]);
      return redirect()->route('plant.add.list', ['id' => $plant->id])->with('success', 'Checklist added successfully.');
    }
  }

  public function removeFromPlant(Request $request, $id, $objectType, $object_id) {
    $plant = Plant::findOrFail($id);
    if ($objectType === 'checklist' && $checklist = Checklist::findOrFail($object_id)) {
      $plant->checklists()->detach($checklist);
      return redirect()->route('plant.add.list', ['id' => $plant->id])->with('success', 'Checklist removed successfully.');
    }
  }

  public function saveDue(Request $request, $id) {
    $plant = Plant::findOrFail($id);
    $plant->items()->syncWithoutDetaching([$request->item_id => ['due' => $request->due]]);
    return redirect()->route('plant.detail', ['id' => $plant->id])->with('success', 'Due date saved successfully.');
  }

  public function removeDue(Request $request, $id) {
    $plant = Plant::findOrFail($id);
    $plant->items()->syncWithoutDetaching([$request->item_id => ['due' => null]]);
    return redirect()->route('plant.detail', ['id' => $plant->id])->with('success', 'Due date removed successfully.');
  }

  public function toggleChecked(Request $request, $id) {
    $plant = Plant::findOrFail($id);
    $plant->items()->syncWithoutDetaching([$request->item_id => ['checked' => $request->checked === 'false' ? now(User::getTimezone(auth()->user())) : null]]);

    // Update statut
    $plant->statut_id = Statut::getStatut($plant)->id;
    $plant->save();

    return redirect()->route('plant.detail', ['id' => $plant->id])->with('success', 'Checklist item updated successfully.');
  }

  public function waterWithoutChemical(Request $request, $id) {
    $plant = Plant::findOrFail($id);
    $plant->waterings()->create(['chemical' => false]);
    return redirect()->route('plant.detail', ['id' => $plant->id])->with('success', 'Watering without chemical done successfully.');
  }

  public function waterWithChemical(Request $request, $id) {
    $plant = Plant::findOrFail($id);
    $plant->waterings()->create(['chemical' => true]);
    return redirect()->route('plant.detail', ['id' => $plant->id])->with('success', 'Watering with chemical done successfully.');
  }
}
