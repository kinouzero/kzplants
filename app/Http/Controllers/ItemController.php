<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use Illuminate\Http\Request;

use App\Models\Item;

class ItemController extends Controller
{
  // Views
  public function index()
  {
    $items = Item::all();
    return view('item.index', compact('items'));
  }

  public function create()
  {
    $item       = null;
    $checklists = Checklist::all();
    return view('item.create', compact('item', 'checklists'));
  }

  public function edit($id)
  {
    $item       = Item::findOrFail($id);
    $checklists = Checklist::all();
    return view('item.edit', compact('item', 'checklists'));
  }

  public function detail($id)
  {
    $item = Item::findOrFail($id);
    return view('item.detail', compact('item'));
  }

  // Actions
  public function store(Request $request)
  {
    $item              = new Item();
    $validatedData     = $request->validate([
      'name'        => 'required|string',
      'checklist_id' => 'required|integer',
      'parent_id'    => 'integer'
    ]);
    $item->name        = $validatedData['name'];
    $item->checklist_id = $validatedData['checklist_id'];
    if ($validatedData['parent_id']) $item->parent_id = $validatedData['parent_id'];
    $item->save();

    return redirect()->route('item.index')->with('success', 'Checklist item created successfully.');
  }

  public function update(Request $request, $id)
  {
    $item = Item::findOrFail($id);
    $item->update($request->all());
    return redirect()->route('item.index')->with('success', 'Checklist item updated successfully.');
  }

  public function destroy($id)
  {
    $item = Item::findOrFail($id);
    $item->delete();
    return redirect()->route('item.index')->with('success', 'Checklist item deleted successfully.');
  }
}
