<?php

namespace App\Http\Controllers;

use App\Models\Statut;
use Illuminate\Http\Request;

class StatutController extends Controller
{
    // Views
    public function index()
    {
        $this->authorize('viewAny', Statut::class);
        $status = Statut::all();

        return view('statut.index', compact('status'));
    }

    public function create()
    {
        $this->authorize('create', Statut::class);
        $statut = null;

        $title = __('ui.create_new', ['item' => __('ui.status')]);

        return view('statut.edit', compact('statut', 'title'));
    }

    public function edit($id)
    {
        $statut = Statut::findOrFail($id);
        $this->authorize('update', $statut);

        $title = __('ui.edit_item', ['item' => __('ui.status'), 'name' => $statut->name]);

        return view('statut.edit', compact('statut', 'title'));
    }

    public function detail($id)
    {
        $statut = Statut::findOrFail($id);
        $this->authorize('view', $statut);

        return view('statut.detail', compact('statut'));
    }

    // Actions
    public function store(Request $request)
    {
        $this->authorize('create', Statut::class);
        $statut = new Statut;

        $validatedData = $request->validate([
            'name' => 'required|string',
            'color' => 'required|string',
        ]);

        $statut->fill($validatedData)->save();

        return back()->with('success', __('ui.created_success', ['item' => __('ui.status')]));
    }

    public function update(Request $request, $id)
    {
        $statut = Statut::findOrFail($id);
        $this->authorize('update', $statut);
        $validatedData = $request->validate([
            'name' => 'required|string',
            'color' => 'required|string',
        ]);
        $statut->update($validatedData);

        return back()->with('success', __('ui.updated_success', ['item' => __('ui.status')]));
    }

    public function destroy($id)
    {
        $statut = Statut::findOrFail($id);
        $this->authorize('delete', $statut);
        $statut->delete();

        return back()->with('success', __('ui.deleted_success', ['item' => __('ui.status')]));
    }
}
