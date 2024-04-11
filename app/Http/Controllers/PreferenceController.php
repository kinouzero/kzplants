<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    $types = [
      view('template.form.select.option', ['value' => 'text', 'title' => 'Text', 'selected' => false]),
      view('template.form.select.option', ['value' => 'number', 'title' => 'Text', 'selected' => false]),
      view('template.form.select.option', ['value' => 'select', 'title' => 'List', 'selected' => false])
    ];

    $template_options = [
      view('template.form.row', [
        'id'        => null,
        'value'     => '',
        'key_value' => '',
        'type'      => 'text',
        'key'       => 'options',
        'label'     => 'Key'
      ]),
      view('template.alert', ['color' => 'secondary', 'class' => null, 'content' => 'No option yet'])
    ];

    return view('preference.edit', compact('preference', 'title', 'types', 'template_options'));
  }

  public function edit($id) {
    $preference = Preference::findOrFail($id);

    $title = sprintf('Edit preference: %s', $preference->name);

    $types = [
      view('template.form.select.option', ['value' => 'text', 'title' => 'Text', 'selected' => $preference->type === 'text']),
      view('template.form.select.option', ['value' => 'number', 'title' => 'Number', 'selected' => $preference->type === 'number']),
      view('template.form.select.option', ['value' => 'select', 'title' => 'List', 'selected' => $preference->type === 'select'])
    ];

    $template_options = [view('template.form.row', [
      'id'        => null,
      'value'     => '',
      'key_value' => '',
      'type'      => 'text',
      'key'       => 'options',
      'label'     => 'Key'
    ])];
    if ($preference->options()) foreach ($preference->options() as $k => $v) $template_options[] = view('template.form.row', [
      'id'        => Str::uuid(),
      'key_value' => $k ?: '',
      'value'     => $v ?: '',
      'type'      => 'text',
      'key'       => 'options',
      'label'     => 'Key'
    ]);
    else $template_options[] = view('template.alert', ['color' => 'secondary', 'class' => null, 'content' => 'No option yet']);

    return view('preference.edit', compact('preference', 'title', 'types', 'template_options'));
  }

  public function detail($id) {
    $preference = Preference::findOrFail($id);

    return view('preference.detail', compact('preference'));
  }

  // Actions
  public function store(Request $request) {
    $preference = new Preference();

    $args = $request->all();

    $validatedData = $request->validate([
      'name'        => 'required|string',
      'key'         => 'required|string',
      'type'        => 'required|string',
      'description' => 'string'
    ]);

    $preference->name        = $validatedData['name'];
    $preference->key         = $validatedData['key'];
    $preference->type        = $validatedData['type'];
    $preference->description = $validatedData['description'];

    // Options
    $options = [];
    if ($args['options']) foreach ($args['options'] as $i => $k) if ($v = $args['values'][$i]) $options[$k] = $v;
    $preference->options = json_encode($options);

    $preference->save();

    return back()->with('success', 'Preference created successfully.');
  }

  public function update(Request $request, $id) {
    $preference = Preference::findOrFail($id);

    $args = $request->all();

    // Options
    $options = [];
    if ($args['options']) foreach ($args['options'] as $i => $k) if ($v = $args['values'][$i]) $options[$k] = $v;
    $args['options'] = json_encode($options);

    $preference->update($args);

    return back()->with('success', 'Preference updated successfully.');
  }

  public function destroy($id) {
    $preference = Preference::findOrFail($id);
    $preference->delete();

    return back()->with('success', 'Preference deleted successfully.');
  }
}
