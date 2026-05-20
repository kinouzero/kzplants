<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\Stage;
use Illuminate\Http\Request;

class StageController extends Controller
{
    // Views
    public function index()
    {
        $this->authorize('viewAny', Stage::class);
        $stages = Stage::with('checklist')->orderBy('order')->get();

        return view('stage.index', compact('stages'));
    }

    public function create()
    {
        $this->authorize('create', Stage::class);
        $stage = null;

        $title = __('ui.create_new', ['item' => __('ui.stage')]);
        $nextOrder = (int) Stage::max('order') + 1;

        return view('stage.edit', compact('stage', 'title', 'nextOrder'));
    }

    public function edit($id)
    {
        $stage = Stage::with('checklist')->findOrFail($id);
        $this->authorize('update', $stage);

        $title = __('ui.edit_item', ['item' => __('ui.stage'), 'name' => $stage->name]);
        $nextOrder = $stage->order;

        return view('stage.edit', compact('stage', 'title', 'nextOrder'));
    }

    // Actions
    public function store(Request $request)
    {
        $this->authorize('create', Stage::class);
        $validatedData = $request->validate([
            'name' => 'required|string',
            'checklist_name' => 'required|string',
            'checklist_icon' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'interval_stage_days' => 'nullable|integer|min:0',
            'interval_item_days' => 'nullable|integer|min:0',
        ]);

        $checklist = new Checklist;
        $checklist->name = $validatedData['checklist_name'];
        $checklist->icon = $validatedData['checklist_icon'] ?? null;
        $checklist->save();

        $stage = new Stage;
        $stage->name = $validatedData['name'];
        $stage->checklist_id = $checklist->id;
        $stage->order = $validatedData['order'] ?? ((int) Stage::max('order') + 1);
        $stage->interval_stage_days = $validatedData['interval_stage_days'] ?? 0;
        $stage->interval_item_days = $validatedData['interval_item_days'] ?? 7;
        $stage->save();

        return back()->with('success', __('ui.created_success', ['item' => __('ui.stage')]));
    }

    public function update(Request $request, $id)
    {
        $stage = Stage::findOrFail($id);
        $this->authorize('update', $stage);
        $validatedData = $request->validate([
            'name' => 'required|string',
            'checklist_name' => 'required|string',
            'checklist_icon' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'interval_stage_days' => 'nullable|integer|min:0',
            'interval_item_days' => 'nullable|integer|min:0',
        ]);

        if ($stage->checklist) {
            $stage->checklist->update([
                'name' => $validatedData['checklist_name'],
                'icon' => $validatedData['checklist_icon'] ?? null,
            ]);
        }

        $stage->update([
            'name' => $validatedData['name'],
            'order' => $validatedData['order'] ?? $stage->order,
            'interval_stage_days' => $validatedData['interval_stage_days'] ?? $stage->interval_stage_days,
            'interval_item_days' => $validatedData['interval_item_days'] ?? $stage->interval_item_days,
        ]);

        return back()->with('success', __('ui.updated_success', ['item' => __('ui.stage')]));
    }

    public function destroy($id)
    {
        $stage = Stage::findOrFail($id);
        $this->authorize('delete', $stage);
        $stage->delete();

        return back()->with('success', __('ui.deleted_success', ['item' => __('ui.stage')]));
    }
}
