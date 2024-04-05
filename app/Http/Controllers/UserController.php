<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Preference;
use App\Models\Role;
use App\Models\User;

class UserController extends Controller {

  // Views
  public function index() {
    $users = User::all();

    return view('user.index', compact('users'));
  }

  public function create() {
    $user = null;
    $roles = Role::all();
    $preferences = Preference::all();

    $title = 'Create new user';

    $template_preferences = [];
    foreach ($preferences as $preference) {

      $options_preferences = [];
      if (($options = $preference->options()) && array_key_exists('props', $options)) foreach ($options['props'] as $k => $v) $options_preferences[] = view('template.form.select.option', ['value' => $k, 'title' => $v, 'selected' => false]);

      if ($preference->type === 'checklist') $template_preferences[] = view('template.form.floating', [
        'type' => 'select',
        'id' => sprintf('preference-%s', $preference->id),
        'name' => sprintf(
          'preference[%s]%s',
          $preference->id,
          $options && array_key_exists('multiple', $options) ? '[]' : ''
        ),
        'label' => $preference->name,
        'options' => implode('', $options_preferences),
        'placeholder' => 'Select',
        'class' => ['parent' => 'mb-3', 'input' => 'select2'],
        'extra' => ['input' => $options && array_key_exists('multiple', $options) ? 'multiple' : ''],
      ]);
      else $template_preferences[] = view('template.form.floating', [
        'type' => $preference->type,
        'id' => sprintf('preference-%s', $preference->id),
        'name' => sprintf('preference_%s', $preference->id),
        'label' => $preference->name,
        'value' => '',
        'class' => ['parent' => 'mb-3'],
        'extra' => null,
      ]);
    }

    return view('user.edit', compact('user', 'roles', 'template_preferences', 'title'));
  }

  public function edit($id) {
    $user        = User::findOrFail($id);
    $roles       = Role::all();
    $preferences = Preference::all();

    $title = sprintf('Edit user: %s', $user->name);

    $template_preferences = [];
    foreach ($preferences as $preference) {
      $userPref = $user ? $user->preferences()->where('preference_id', $preference->id)->first() : null;

      $options_preferences = [];
      if (($options = $preference->options()) && array_key_exists('props', $options)) foreach ($options['props'] as $k => $v) $options_preferences[] = view('template.form.select.option', ['value' => $k, 'title' => $v, 'selected' => $userPref && $userPref->pivot->value == $k]);

      if ($preference->type === 'checklist') $template_preferences[] = view('template.form.floating', [
        'type' => 'select',
        'id' => sprintf('preference-%s', $preference->id),
        'name' => sprintf(
          'preferences[%s]%s',
          $preference->id,
          $options && array_key_exists('multiple', $options) ? '[]' : ''
        ),
        'label' => $preference->name,
        'options' => implode('', $options_preferences),
        'placeholder' => 'Select',
        'class' => ['parent' => 'mb-3', 'input' => 'select2'],
        'extra' => ['input' => $options && array_key_exists('multiple', $options) ? 'multiple' : ''],
      ]);
      else $template_preferences[] = view('template.form.floating', [
        'type' => 'text',
        'id' => sprintf('preference-%s', $preference->id),
        'name' => sprintf('preferences[%s]', $preference->id),
        'label' => $preference->name,
        'value' => $userPref ? $userPref->pivot->value : '',
        'class' => ['parent' => 'mb-3'],
        'extra' => null,
      ]);
    }

    return view('user.edit', compact('user', 'roles', 'preferences', 'title', 'template_preferences'));
  }

  public function detail($id) {
    $user = User::findOrFail($id);

    return view('user.detail', compact('user'));
  }

  // Actions
  public function store(Request $request) {
    $user = new User();

    $args = $request->all();

    $validatedData = $request->validate(['name' => 'required|string']);

    $user->name = $validatedData['name'];
    $user->save();

    // Roles
    $roles = Role::whereIn('id', $args['roles'])->get();
    $user->roles()->attach($roles ? $roles->pluck('id')->toArray() : []);

    // Preferences
    $values = [];
    if ($preferences = $args['preferences']) foreach ($preferences as $preference_id => $v) if ($v) $values[] = [
      'user_id'       => $user->id,
      'preference_id' => $preference_id,
      'value'         => $v
    ];
    $user->preferences()->attach($values);

    return back()->with('success', 'User created successfully.');
  }

  public function update(Request $request, $id) {
    $user = User::findOrFail($id);

    $args = $request->all();

    // Password
    if (!$args['password']) unset($args['password'], $args['password2']);
    else if (!$args['password2'] || ($args['password'] !== $args['password2'])) return redirect()->back()->withInput()->with('error', 'Les mots de passes ne correspondent pas');

    // Roles
    $roles = isset($args['roles']) ? Role::whereIn('id', $args['roles'])->get() : null;
    $user->roles()->sync($roles ? $roles->pluck('id')->toArray() : []);
    unset($args['roles']);

    // Preferences
    $values = [];
    if ($preferences = $args['preferences']) foreach ($preferences as $preference_id => $v) if ($v) $values[] = [
      'user_id'       => $user->id,
      'preference_id' => $preference_id,
      'value'         => $v
    ];
    $user->preferences()->sync($values);
    unset($args['preferences']);

    $user->update($args);

    return back()->with('success', 'User updated successfully.');
  }

  public function destroy($id) {
    $user = User::findOrFail($id);
    $user->delete();

    return back()->with('success', 'User deleted successfully.');
  }
}
