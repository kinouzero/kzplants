<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Preference;

class PreferenceController extends Controller {

  // Views
  public function index() {
    $preferences = Preference::all();

    return view('preference.index', compact('preferences'));
  }

  public function create() {
    $preference = null;

    $title = 'Create new preference';

    return view('preference.edit', compact('preference', 'title'));
  }

  public function edit($id) {
    $preference = Preference::findOrFail($id);

    $title = sprintf('Edit preference: %s', $preference->name);

    return view('preference.edit', compact('preference', 'title'));
  }

  public function detail($id) {
    $preference = Preference::findOrFail($id);

    return view('preference.detail', compact('preference'));
  }

  // Actions
  public function store(Request $request) {
    $preference = new Preference();

    $validatedData = $request->validate(['name' => 'required|string']);

    $preference->name = $validatedData['name'];
    $preference->save();

    return back()->with('success', 'Preference created successfully.');
  }

  public function update(Request $request, $id) {
    $preference = Preference::findOrFail($id);
    $preference->update($request->all());

    return back()->with('success', 'Preference updated successfully.');
  }

  public function destroy($id) {
    $preference = Preference::findOrFail($id);
    $preference->delete();

    return back()->with('success', 'Preference deleted successfully.');
  }
}
