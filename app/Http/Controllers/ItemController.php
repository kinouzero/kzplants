<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Views
    public function index()
    {
        $this->authorize('viewAny', Item::class);
        $items = Item::all();

        return view('item.index', compact('items'));
    }

    public function create()
    {
        $this->authorize('create', Item::class);
        $item = null;
        $checklists = Checklist::all();

        $title = __('ui.create_new', ['item' => __('ui.item')]);

        $options_checklists = $options_parents = [];
        foreach ($checklists as $checklist) {
            $options_checklists[] = view('template.form.select.option', ['value' => $checklist->id, 'title' => $checklist->name, 'selected' => false]);
        }
        if ($item && $item->checklist) {
            foreach ($item->checklist->items as $_item) {
                if ($item && ($item->id === $_item->id || ($_item->child && $item->id !== $_item->child->id))) {
                    $options_parents[] = view('template.form.select.option', ['value' => $_item->id, 'title' => $_item->name, 'selected' => false]);
                }
            }
        }

        return view('item.edit', compact('item', 'options_checklists', 'options_parents', 'title'));
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $this->authorize('update', $item);
        $checklists = Checklist::all();

        $title = __('ui.edit_item', ['item' => __('ui.item'), 'name' => $item->name]);

        $options_checklists = $options_parents = [];
        foreach ($checklists as $checklist) {
            $options_checklists[] = view('template.form.select.option', ['value' => $checklist->id, 'title' => $checklist->name, 'selected' => $item->checklist->id === $checklist->id]);
        }
        if ($item->checklist) {
            foreach ($item->checklist->items as $_item) {
                if ($item->id !== $_item->id || ($_item->child && $item->id !== $_item->child->id)) {
                    $options_parents[] = view('template.form.select.option', ['value' => $_item->id, 'title' => $_item->name, 'selected' => $item->parent && $item->parent->id === $_item->id]);
                }
            }
        }

        return view('item.edit', compact('item', 'options_checklists', 'options_parents', 'title'));
    }

    public function detail($id)
    {
        $item = Item::findOrFail($id);
        $this->authorize('view', $item);

        return view('item.detail', compact('item'));
    }

    // Actions
    public function store(Request $request)
    {
        $this->authorize('create', Item::class);
        $item = new Item;

        $validatedData = $request->validate([
            'name' => 'required|string',
            'checklist_id' => 'required|integer|exists:checklists,id',
            'parent_id' => 'nullable|integer|exists:items,id',
        ]);

        $item->name = $validatedData['name'];
        $item->checklist_id = $validatedData['checklist_id'];
        if ($validatedData['parent_id']) {
            $item->parent_id = $validatedData['parent_id'];
        }

        $item->save();

        return back()->with('success', __('ui.checklist_item_created'));
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $this->authorize('update', $item);
        $validatedData = $request->validate([
            'name' => 'required|string',
            'checklist_id' => 'required|integer|exists:checklists,id',
            'parent_id' => 'nullable|integer|exists:items,id',
        ]);
        $item->update($validatedData);

        return back()->with('success', __('ui.checklist_item_updated'));
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $this->authorize('delete', $item);
        $item->delete();

        return back()->with('success', __('ui.checklist_item_deleted'));
    }
}
