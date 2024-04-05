<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Checklist;
use App\Models\Item;

class ChecklistController extends Controller {

  // Views
  public function index() {
    $checklists = Checklist::all();

    return view('checklist.index', compact('checklists'));
  }

  public function create() {
    $checklist  = null;
    $checklists = Checklist::all();

    $title = 'Create new checklist';

    $options   = [];
    foreach ($checklists as $_checklist) $options[] = view('template.form.select.option', ['value' => $_checklist->id, 'title' => $_checklist->name, 'selected' => false]);

    return view('checklist.edit', compact('checklist', 'options', 'title'));
  }

  public function edit($id) {
    $checklist  = Checklist::findOrFail($id);
    $checklists = Checklist::all();

    $title = sprintf('Edit checklist: %s', $checklist->name);

    $options   = [];
    foreach ($checklists as $_checklist) $options[] = view('template.form.select.option', ['value' => $_checklist->id, 'title' => $_checklist->name, 'selected' => $checklist->parents->contains('id', $_checklist->id)]);

    return view('checklist.edit', compact('checklist', 'options', 'title'));
  }

  // Actions
  public function store(Request $request) {
    $checklist = new Checklist();

    $validatedData = $request->validate([
      'name' => 'required|string',
      'icon' => 'string'
    ]);

    $checklist->name = $validatedData['name'];
    $checklist->icon = $validatedData['icon'];
    $checklist->save();

    return back()->with('success', 'Checklist created successfully.');
  }

  public function update(Request $request, $id) {
    $checklist = Checklist::findOrFail($id);
    $checklist->update($request->all());

    return back()->with('success', 'Checklist updated successfully.');
  }

  public function destroy($id) {
    $checklist = Checklist::findOrFail($id);
    $checklist->delete();

    return back()->with('success', 'Checklist deleted successfully.');
  }

  public function getItems(Request $request, $id) {
    $checklist = Checklist::findOrFail($id);

    return $checklist->items;
  }
}
