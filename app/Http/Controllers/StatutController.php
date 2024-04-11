<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Statut;

class StatutController extends Controller {

  // Views
  public function index() {
    $status = Statut::all();

    return view('statut.index', compact('status'));
  }

  public function create() {
    $statut = null;

    $title = 'Create new statut';

    return view('statut.edit', compact('statut', 'title'));
  }

  public function edit($id) {
    $statut = Statut::findOrFail($id);

    $title = sprintf('Edit statut: %s', $statut->name);

    return view('statut.edit', compact('statut', 'title'));
  }

  public function detail($id) {
    $statut = Statut::findOrFail($id);

    return view('statut.detail', compact('statut'));
  }

  // Actions
  public function store(Request $request) {
    $statut = new Statut();

    $validatedData = $request->validate(['name' => 'required|string']);

    $statut->name = $validatedData['name'];
    $statut->save();

    return back()->with('success', 'Statut created successfully.');
  }

  public function update(Request $request, $id) {
    $statut = Statut::findOrFail($id);
    $statut->update($request->all());

    return back()->with('success', 'Statut updated successfully.');
  }

  public function destroy($id) {
    $statut = Statut::findOrFail($id);
    $statut->delete();

    return back()->with('success', 'Statut deleted successfully.');
  }
}
