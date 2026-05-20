<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    // Views
    public function index()
    {
        $this->authorize('viewAny', Property::class);
        $properties = Property::all();

        return view('property.index', compact('properties'));
    }

    public function create()
    {
        $this->authorize('create', Property::class);
        $property = null;

        $title = __('ui.create_new', ['item' => __('ui.property')]);

        return view('property.edit', compact('property', 'title'));
    }

    public function edit($id)
    {
        $property = Property::findOrFail($id);
        $this->authorize('update', $property);

        $title = __('ui.edit_item', ['item' => __('ui.property'), 'name' => $property->name]);

        return view('property.edit', compact('property', 'title'));
    }

    public function detail($id)
    {
        $property = Property::findOrFail($id);
        $this->authorize('view', $property);

        return view('property.detail', compact('property'));
    }

    // Actions
    public function store(Request $request)
    {
        $this->authorize('create', Property::class);
        $property = new Property;

        $validatedData = $request->validate(['name' => 'required|string']);

        $property->name = $validatedData['name'];
        $property->save();

        return back()->with('success', __('ui.created_success', ['item' => __('ui.property')]));
    }

    public function update(Request $request, $id)
    {
        $property = Property::findOrFail($id);
        $this->authorize('update', $property);
        $validatedData = $request->validate(['name' => 'required|string']);
        $property->update($validatedData);

        return back()->with('success', __('ui.updated_success', ['item' => __('ui.property')]));
    }

    public function destroy($id)
    {
        $property = Property::findOrFail($id);
        $this->authorize('delete', $property);
        $property->delete();

        return back()->with('success', __('ui.deleted_success', ['item' => __('ui.property')]));
    }
}
