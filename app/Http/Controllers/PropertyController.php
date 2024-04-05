<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Property;

class PropertyController extends Controller {

  // Views
  public function index() {
    $properties = Property::all();

    return view('property.index', compact('properties'));
  }

  public function create() {
    $property = null;

    return view('property.create', compact('property'));
  }

  public function edit($id) {
    $property = Property::findOrFail($id);

    return view('property.edit', compact('property'));
  }

  public function detail($id) {
    $property = Property::findOrFail($id);

    return view('property.detail', compact('property'));
  }

  // Actions
  public function store(Request $request) {
    $property = new Property();

    $validatedData = $request->validate(['name' => 'required|string']);

    $property->name = $validatedData['name'];
    $property->save();

    return back()->with('success', 'Property created successfully.');
  }

  public function update(Request $request, $id) {
    $property = Property::findOrFail($id);
    $property->update($request->all());

    return back()->with('success', 'Property updated successfully.');
  }

  public function destroy($id) {
    $property = Property::findOrFail($id);
    $property->delete();

    return back()->with('success', 'Property deleted successfully.');
  }
}
