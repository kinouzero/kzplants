<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Checklist;
use App\Models\Item;

class ChecklistController extends Controller
{
    // Views
    public function index()
    {
        $checklists = Checklist::all();
        return view('checklist.index', compact('checklists'));
    }

    public function create()
    {
        $checklist  = null;
        $checklists = Checklist::all();
        $items      = Item::all();
        return view('checklist.create', compact('checklist', 'checklists', 'items'));
    }

    public function edit($id)
    {
        $checklist  = Checklist::findOrFail($id);
        $checklists = Checklist::all();
        $items      = Item::all();
        return view('checklist.edit', compact('checklist', 'checklists', 'items'));
    }

    public function detail($id)
    {
        $checklist = Checklist::findOrFail($id);
        return view('checklist.detail', compact('checklist'));
    }

    // Actions
    public function store(Request $request)
    {
        $checklist       = new Checklist();
        $args            = $request->all();
        $validatedData   = $request->validate(['name' => 'required|string', 'icon' => 'string']);
        $checklist->name = $validatedData['name'];
        $checklist->icon = $validatedData['icon'];
        $checklist->save();

        // Checklist items
        $items = isset($args['items']) ? Item::whereIn('id', $args['items'])->get() : null;
        if ($items && $items->count() > 0) $checklist->items()->sync($items->pluck('id')->toArray());

        return redirect()->route('checklist.index')->with('success', 'Checklist created successfully.');
    }

    public function update(Request $request, $id)
    {
        $checklist = Checklist::findOrFail($id);
        $args      = $request->all();

        // Checklist items
        $checklist->items()->detach();
        $items = isset($args['items']) ? Item::whereIn('id', $args['items'])->get() : null;
        if ($items && $items->count() > 0) $checklist->items()->sync($items->pluck('id')->toArray());
        unset($args['items']);

        $checklist->update($args);
        return redirect()->route('checklist.index')->with('success', 'Checklist updated successfully.');
    }

    public function destroy($id)
    {
        $checklist = Checklist::findOrFail($id);
        $checklist->delete();
        return redirect()->route('checklist.index')->with('success', 'Checklist deleted successfully.');
    }

    public function getItems(Request $request, $id)
    {
        $checklist = Checklist::findOrFail($id);
        return $checklist->items;
    }
}
