<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

use App\Models\Strain;
use App\Models\Tag;

class StrainController extends Controller {
  // Views
  public function index() {
    $strains = Strain::all();
    return view('strain.index', compact('strains'));
  }

  public function detail($id) {
    $strain = Strain::findOrFail($id);
    return view('strain.detail', compact('strain'));
  }

  public function create() {
    $strain     = null;
    $tags       = Tag::all();
    $properties = Property::all();
    return view('strain.create', compact('strain', 'tags', 'properties'));
  }

  public function edit($id) {
    $strain     = Strain::findOrFail($id);
    $tags       = Tag::all();
    $properties = Property::all();
    return view('strain.edit', compact('strain', 'tags', 'properties'));
  }

  // Actions
  public function store(Request $request) {
    $strain        = new Strain();
    $args          = $request->all();
    $validatedData = $request->validate(['name' => 'required|string']);
    $strain->name  = $validatedData['name'];
    $strain->save();

    // Tags
    $tags = isset($args['tags']) ? Tag::whereIn('id', $args['tags'])->get() : null;
    if ($tags && $tags->count() > 0) $strain->tags()->sync($tags->pluck('id')->toArray());

    // Properties
    $propertyValue = [];
    if ($values = $args['values']) foreach ($values as $k => $v) if ($v) $propertyValue[] = [
      'strain_id' => $strain->id,
      'property_id' => $args['properties'][$k],
      'value' => $v
    ];
    if ($propertyValue) $strain->properties()->sync($propertyValue);

    return redirect()->route('strain.index')->with('success', 'Strain created successfully.');
  }

  public function update(Request $request, $id) {
    $strain = Strain::findOrFail($id);
    $args   = $request->all();

    // Tags
    $tags = isset($args['tags']) ? Tag::whereIn('id', $args['tags'])->get() : null;
    $strain->tags()->detach();
    if ($tags && $tags->count() > 0) $strain->tags()->sync($tags->pluck('id')->toArray());
    unset($args['tags']);

    // Properties
    $propertyValue = [];
    if ($values = $args['values']) foreach ($values as $k => $v) if ($v) $propertyValue[] = [
      'strain_id' => $strain->id,
      'property_id' => $args['properties'][$k],
      'value' => $v
    ];
    $strain->properties()->detach();
    if ($propertyValue) $strain->properties()->sync($propertyValue);
    unset($args['values'], $args['properties']);

    $strain->update($args);

    return redirect()->route('strain.index')->with('success', 'Strain updated successfully.');
  }

  public function destroy($id) {
    $strain = Strain::findOrFail($id);
    $strain->delete();
    return redirect()->route('strain.index')->with('success', 'Strain deleted successfully.');
  }
}
