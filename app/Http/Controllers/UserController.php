<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Preference;
use App\Models\Role;
use App\Models\User;

class UserController extends Controller
{
  // Views
  public function index()
  {
    $users = User::all();
    return view('user.index', compact('users'));
  }

  public function create()
  {
    $user = null;
    $roles = Role::all();
    return view('user.create', compact('user', 'roles'));
  }

  public function edit($id)
  {
    $user        = User::findOrFail($id);
    $roles       = Role::all();
    $preferences = Preference::all();
    return view('user.edit', compact('user', 'roles', 'preferences'));
  }

  public function detail($id)
  {
    $user = User::findOrFail($id);
    return view('user.detail', compact('user'));
  }

  // Actions
  public function store(Request $request)
  {
    $user          = new User();
    $args          = $request->all();
    $validatedData = $request->validate(['name' => 'required|string']);
    $user->name    = $validatedData['name'];
    $user->save();

    // Roles
    $roles = Role::whereIn('id', $args['roles'])->get();
    if ($roles->count() > 0) $user->roles()->sync($roles->pluck('id')->toArray());

    // Preferences
    $values = [];
    if ($preferences = $args['preferences']) foreach ($preferences as $preference_id => $v) if ($v) $values[] = [
      'user_id' => $user->id,
      'preference_id' => $preference_id,
      'value' => $v
    ];
    if ($values) $user->preferences()->sync($values);

    return redirect()->route('user.edit', ['id' => $user->id])->with('success', 'User created successfully.');
  }

  public function update(Request $request, $id)
  {
    $user = User::findOrFail($id);
    $args = $request->all();

    // Password
    if (!$args['password']) unset($args['password'], $args['password2']);
    else if (!$args['password2'] || ($args['password'] !== $args['password2'])) return redirect()->back()->with('error', 'Les mots de passes ne correspondent pas');

    // Roles
    if ($roles = Role::whereIn('id', $args['roles'])->get()) unset($args['roles']);
    if ($roles->count() > 0) $user->roles()->sync($roles->pluck('id')->toArray());

    // Preferences
    $values = [];
    if ($preferences = $args['preferences']) foreach ($preferences as $preference_id => $v) if ($v) $values[] = [
      'user_id' => $user->id,
      'preference_id' => $preference_id,
      'value' => $v
    ];
    if ($values) $user->preferences()->sync($values);
    unset($args['preferences']);

    $user->update($args);

    return redirect()->route('user.index')->with('success', 'User updated successfully.');
  }

  public function destroy($id)
  {
    $user = User::findOrFail($id);
    $user->delete();
    return redirect()->route('user.index')->with('success', 'User deleted successfully.');
  }
}
