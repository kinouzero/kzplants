<?php

namespace App\Http\Controllers;

use App\Models\Picture;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Property;
use App\Models\Strain;
use App\Models\Tag;

class StrainController extends Controller {

  // Views
  public function index() {
    $strains = Strain::all();

    return view('strain.index', compact('strains'));
  }

  public function create() {
    $strain     = null;
    $tags       = Tag::all();
    $properties = Property::all();

    $title = 'Create new strain';

    $option_tags = $option_properties = [];
    foreach ($tags as $tag) $option_tags[] = view('template.form.select.option', ['value' => $tag->id, 'title' => $tag->name, 'selected' => false]);
    foreach ($properties as $property) $option_properties[] = view('template.form.select.option', ['value' => $property->id, 'title' => $property->name, 'selected' => false]);

    $template_properties = [
      view('template.form.row', [
        'id'      => null,
        'value'   => '',
        'type'    => 'select',
        'key'     => 'properties',
        'label'   => 'Property',
        'options' => $option_properties,
      ]),
      view('template.alert', ['color' => 'secondary', 'class' => 'mb-0', 'content' => 'No property yet'])
    ];

    return view('strain.edit', compact('strain', 'option_tags', 'template_properties', 'title'));
  }

  public function edit($id) {
    $strain     = Strain::findOrFail($id);
    $tags       = Tag::all();
    $properties = Property::all();

    $title = sprintf('Edit strain: %s', $strain->name);

    $alert = null;
    $option_tags = $option_properties = $value_properties = [];
    foreach ($tags as $tag) $option_tags[] = view('template.form.select.option', ['value' => $tag->id, 'title' => $tag->name, 'selected' => $strain->tags->contains('id', $tag->id)]);
    foreach ($properties as $property) {
      $option_properties['clone'][] = view('template.form.select.option', ['value' => $property->id, 'title' => $property->name, 'selected' => false]);
      $value_properties['clone'] = null;
      if ($strain->properties->count() > 0) foreach ($strain->properties as $strainProperty) {
        $option_properties[$strainProperty->pivot->property_id][] = view('template.form.select.option', ['value' => $property->id, 'title' => $property->name, 'selected' => $strainProperty->pivot->property_id === $property->id]);
        $value_properties[$strainProperty->pivot->property_id] = $strainProperty->pivot->value;
      }
      else $alert = view('template.alert', ['color' => 'secondary', 'class' => 'mb-0', 'content' => 'No property yet']);
    }

    $template_properties = [];
    foreach ($option_properties as $k => $options) $template_properties[] = view('template.form.row', [
      'id'      => $k === 'clone' ? null : Str::uuid(),
      'value'   => $value_properties[$k] ?: '',
      'type'    => 'select',
      'key'     => 'properties',
      'label'   => 'Property',
      'options' => $options,
    ]);

    if ($alert) $template_properties[] = $alert;

    return view('strain.edit', compact('strain', 'option_tags', 'template_properties', 'title'));
  }

  public function pictures($id) {
    $strain = Strain::findOrFail($id);

    return view('strain.pictures', compact('strain'));
  }

  // Actions
  public function store(Request $request) {
    $strain = new Strain();

    $args = $request->all();

    $validatedData = $request->validate(['name' => 'required|string']);

    $strain->name = $validatedData['name'];
    $strain->save();

    // Tags
    $tags = isset($args['tags']) ? Tag::whereIn('id', $args['tags'])->get() : null;
    $strain->tags()->attach($tags ? $tags->pluck('id')->toArray() : []);

    // Properties
    $propertyValue = [];
    if ($values = $args['values']) foreach ($values as $k => $v) if ($v) $propertyValue[] = [
      'strain_id'   => $strain->id,
      'property_id' => $args['properties'][$k],
      'value'       => $v
    ];
    $strain->properties()->attach($propertyValue);

    return back()->with('success', 'Strain created successfully.');
  }

  public function update(Request $request, $id) {
    $strain = Strain::findOrFail($id);

    $args = $request->all();

    // Tags
    $tags = isset($args['tags']) ? Tag::whereIn('id', $args['tags'])->get() : null;
    $strain->tags()->sync($tags ? $tags->pluck('id')->toArray() : []);
    unset($args['tags']);

    // Properties
    $propertyValue = [];
    if ($values = $args['values']) foreach ($values as $k => $v) if ($v) $propertyValue[] = [
      'strain_id'   => $strain->id,
      'property_id' => $args['properties'][$k],
      'value'       => $v
    ];
    $strain->properties()->sync($propertyValue);
    unset($args['values'], $args['properties']);

    $strain->update($args);

    return back()->with('success', 'Strain updated successfully.');
  }

  public function destroy($id) {
    $strain = Strain::findOrFail($id);
    $strain->delete();

    return back()->with('success', 'Strain deleted successfully.');
  }


  public function addToStrain(Request $request, $id, $objectType, $object_id) {
    $strain = Strain::findOrFail($id);
    if ($objectType === 'default-picture' && $picture = Picture::findOrFail($object_id)) {
      $strain->pictures()->updateExistingPivot($strain->pictures()->pluck('id'), ['default' => false]);
      $strain->pictures()->updateExistingPivot($picture->id, ['default' => true]);

      return back()->with('success', 'Default picture added successfully.');
    }
  }

  public function removeFromStrain(Request $request, $id, $objectType, $object_id) {
    $strain = Strain::findOrFail($id);
    if ($objectType === 'default-picture' && $picture = Picture::findOrFail($object_id)) {
      $strain->pictures()->updateExistingPivot($picture->id, ['default' => false]);

      return back()->with('success', 'Default picture removed successfully.');
    }
  }
}
