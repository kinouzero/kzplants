<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use Illuminate\Http\Request;

use App\Models\Item;

class ItemController extends Controller {

  // Views
  public function index() {
    $items = Item::all();

    return view('item.index', compact('items'));
  }

  public function create() {
    $item       = null;
    $checklists = Checklist::all();

    $title = 'Create new item';

    $options_checklists = $options_parents = [];
    foreach ($checklists as $checklist) $options_checklists[] = view('template.form.select.option', ['value' => $checklist->id, 'title' => $checklist->name, 'selected' => false]);
    if ($item && $item->checklist) foreach ($item->checklist->items as $_item) if ($item && ($item->id === $_item->id || ($_item->child && $item->id !== $_item->child->id))) $options_parents[] = view('template.form.select.option', ['value' => $_item->id, 'title' => $_item->name, 'selected' => false]);

    return view('item.edit', compact('item', 'options_checklists', 'options_parents', 'title'));
  }

  public function edit($id) {
    $item       = Item::findOrFail($id);
    $checklists = Checklist::all();

    $title = sprintf('Edit item: %s', $item->name);

    $options_checklists = $options_parents = [];
    foreach ($checklists as $checklist) $options_checklists[] = view('template.form.select.option', ['value' => $checklist->id, 'title' => $checklist->name, 'selected' => $item->checklist->id === $checklist->id]);
    if ($item->checklist) foreach ($item->checklist->items as $_item) if ($item->id !== $_item->id || ($_item->child && $item->id !== $_item->child->id)) $options_parents[] = view('template.form.select.option', ['value' => $_item->id, 'title' => $_item->name, 'selected' => $item->parent && $item->parent->id === $_item->id]);

    return view('item.edit', compact('item', 'options_checklists', 'options_parents', 'title'));
  }

  public function detail($id) {
    $item = Item::findOrFail($id);

    return view('item.detail', compact('item'));
  }

  // Actions
  public function store(Request $request) {
    $item = new Item();

    $validatedData = $request->validate([
      'name'         => 'required|string',
      'checklist_id' => 'required|integer',
      'parent_id'    => 'integer'
    ]);

    $item->name         = $validatedData['name'];
    $item->checklist_id = $validatedData['checklist_id'];
    if ($validatedData['parent_id']) $item->parent_id = $validatedData['parent_id'];

    $item->save();

    return back()->with('success', 'Checklist item created successfully.');
  }

  public function update(Request $request, $id) {
    $item = Item::findOrFail($id);
    $item->update($request->all());

    return back()->with('success', 'Checklist item updated successfully.');
  }

  public function destroy($id) {
    $item = Item::findOrFail($id);
    $item->delete();

    return back()->with('success', 'Checklist item deleted successfully.');
  }
}
