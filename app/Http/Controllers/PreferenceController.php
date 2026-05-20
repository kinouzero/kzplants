<?php

namespace App\Http\Controllers;

use App\Models\Preference;
use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    // Views
    public function index()
    {
        $this->authorize('viewAny', Preference::class);
        $preferences = Preference::all();

        return view('preference.index', compact('preferences'));
    }

    public function create()
    {
        $this->authorize('create', Preference::class);
        $preference = null;

        $title = __('ui.create_new', ['item' => __('ui.preference')]);

        return view('preference.edit', compact('preference', 'title'));
    }

    public function edit($id)
    {
        $preference = Preference::findOrFail($id);
        $this->authorize('update', $preference);

        $title = __('ui.edit_item', ['item' => __('ui.preference'), 'name' => $preference->name]);

        return view('preference.edit', compact('preference', 'title'));
    }

    public function detail($id)
    {
        $preference = Preference::findOrFail($id);
        $this->authorize('view', $preference);

        return view('preference.detail', compact('preference'));
    }

    // Actions
    public function store(Request $request)
    {
        $this->authorize('create', Preference::class);
        $preference = new Preference;

        $validatedData = $request->validate(['name' => 'required|string']);

        $preference->name = $validatedData['name'];
        $preference->save();

        return back()->with('success', __('ui.created_success', ['item' => __('ui.preference')]));
    }

    public function update(Request $request, $id)
    {
        $preference = Preference::findOrFail($id);
        $this->authorize('update', $preference);
        $validatedData = $request->validate(['name' => 'required|string']);
        $preference->update($validatedData);

        return back()->with('success', __('ui.updated_success', ['item' => __('ui.preference')]));
    }

    public function destroy($id)
    {
        $preference = Preference::findOrFail($id);
        $this->authorize('delete', $preference);
        $preference->delete();

        return back()->with('success', __('ui.deleted_success', ['item' => __('ui.preference')]));
    }
}
