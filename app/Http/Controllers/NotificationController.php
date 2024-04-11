<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Notification;
use App\Models\User;

class NotificationController extends Controller {

  // Views
  public function index() {
    $notifications = Notification::all();

    return view('notification.index', compact('notifications'));
  }

  public function create() {
    $notification = null;
    $users        = User::all();

    $title = 'Create new notification';

    $options   = [];
    foreach ($users as $user) {
      if ($user->id === auth()->user()->id || $notification && $notification->creator()->id === $user->id) continue;
      $options[] = view('template.form.select.option', ['value' => $user->id, 'title' => $user->name, 'selected' => false]);
    }

    return view('notification.edit', compact('notification', 'options', 'title'));
  }

  public function edit($id) {
    $notification = Notification::findOrFail($id);
    $users        = User::all();

    $title = sprintf('Edit notification: %s', $notification->name);

    $options   = [];
    foreach ($users as $user) {
      if ($user->id === auth()->user()->id || $notification && $notification->creator()->id === $user->id) continue;
      $options[] = view('template.form.select.option', ['value' => $user->id, 'title' => $user->name, 'selected' => false]);
    }

    return view('notification.edit', compact('notification', 'options', 'title'));
  }

  public function detail($id) {
    $notification = Notification::findOrFail($id);

    return view('notification.detail', compact('notification'));
  }

  // Actions
  public function store(Request $request) {
    $notification = new Notification();

    $validatedData = $request->validate([
      'name'          => 'required|string',
      'description'   => 'string'
    ]);

    $notification->name        = $validatedData['name'];
    $notification->description = $validatedData['description'];

    // Configuration
    $notification->configuration = json_encode([]);

    $notification->save();

    return back()->with('success', 'Notification created successfully.');
  }

  public function update(Request $request, $id) {
    $notification = Notification::findOrFail($id);

    $args = $request->all();

    // Configuration
    $configuration = json_encode([]);
    unset($args['configuration']);

    $notification->update($args);

    return back()->with('success', 'Notification updated successfully.');
  }

  public function destroy($id) {
    $notification = Notification::findOrFail($id);
    $notification->delete();

    return back()->with('success', 'Notification deleted successfully.');
  }
}
