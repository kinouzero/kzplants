<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Views
    public function index()
    {
        $this->authorize('viewAny', Notification::class);
        $notifications = Notification::all();

        return view('notification.index', compact('notifications'));
    }

    public function create()
    {
        $this->authorize('create', Notification::class);
        $notification = null;
        $users = User::all();

        $title = __('ui.create_new', ['item' => __('ui.notification')]);
        $config = [
            'channels' => [],
            'mail_subject' => '',
            'ntfy_url' => '',
            'ntfy_topic' => '',
            'ntfy_priority' => '',
            'ntfy_tags' => '',
        ];

        $options = [];
        foreach ($users as $user) {
            if ($user->id === auth()->user()->id || $notification && $notification->creator()->id === $user->id) {
                continue;
            }
            $options[] = view('template.form.select.option', ['value' => $user->id, 'title' => $user->name, 'selected' => false]);
        }

        return view('notification.edit', compact('notification', 'options', 'title', 'config'));
    }

    public function edit($id)
    {
        $notification = Notification::findOrFail($id);
        $this->authorize('update', $notification);
        $users = User::all();

        $title = __('ui.edit_item', ['item' => __('ui.notification'), 'name' => $notification->name]);
        $config = array_merge([
            'channels' => [],
            'mail_subject' => '',
            'ntfy_url' => '',
            'ntfy_topic' => '',
            'ntfy_priority' => '',
            'ntfy_tags' => '',
        ], $notification->config());

        $options = [];
        foreach ($users as $user) {
            if ($user->id === auth()->user()->id || $notification && $notification->creator()->id === $user->id) {
                continue;
            }
            $options[] = view('template.form.select.option', ['value' => $user->id, 'title' => $user->name, 'selected' => false]);
        }

        return view('notification.edit', compact('notification', 'options', 'title', 'config'));
    }

    public function detail($id)
    {
        $notification = Notification::findOrFail($id);
        $this->authorize('view', $notification);

        return view('notification.detail', compact('notification'));
    }

    // Actions
    public function store(Request $request)
    {
        $this->authorize('create', Notification::class);
        $notification = new Notification;

        $validatedData = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'channels' => 'array',
            'channels.*' => 'in:mail,ntfy',
            'mail_subject' => 'nullable|string',
            'ntfy_url' => 'nullable|string',
            'ntfy_topic' => 'nullable|string',
            'ntfy_priority' => 'nullable|integer|min:1|max:5',
            'ntfy_tags' => 'nullable|string',
            'users' => 'array',
            'users.*' => 'integer|exists:users,id',
        ]);

        $notification->name = $validatedData['name'];
        $notification->description = $validatedData['description'];

        $notification->configuration = json_encode([
            'channels' => $validatedData['channels'] ?? [],
            'mail_subject' => $validatedData['mail_subject'] ?? null,
            'ntfy_url' => $validatedData['ntfy_url'] ?? null,
            'ntfy_topic' => $validatedData['ntfy_topic'] ?? null,
            'ntfy_priority' => $validatedData['ntfy_priority'] ?? null,
            'ntfy_tags' => $validatedData['ntfy_tags'] ?? null,
        ]);

        $notification->save();

        $userIds = $validatedData['users'] ?? [];
        $sync = [auth()->id() => ['creator' => true, 'active' => true]];
        foreach ($userIds as $userId) {
            $sync[$userId] = ['creator' => false, 'active' => true];
        }
        $notification->users()->sync($sync);

        return back()->with('success', __('ui.created_success', ['item' => __('ui.notification')]));
    }

    public function update(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);
        $this->authorize('update', $notification);

        $validatedData = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'channels' => 'array',
            'channels.*' => 'in:mail,ntfy',
            'mail_subject' => 'nullable|string',
            'ntfy_url' => 'nullable|string',
            'ntfy_topic' => 'nullable|string',
            'ntfy_priority' => 'nullable|integer|min:1|max:5',
            'ntfy_tags' => 'nullable|string',
            'users' => 'array',
            'users.*' => 'integer|exists:users,id',
        ]);
        $notification->update([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'] ?? null,
            'configuration' => json_encode([
                'channels' => $validatedData['channels'] ?? [],
                'mail_subject' => $validatedData['mail_subject'] ?? null,
                'ntfy_url' => $validatedData['ntfy_url'] ?? null,
                'ntfy_topic' => $validatedData['ntfy_topic'] ?? null,
                'ntfy_priority' => $validatedData['ntfy_priority'] ?? null,
                'ntfy_tags' => $validatedData['ntfy_tags'] ?? null,
            ]),
        ]);

        $userIds = $validatedData['users'] ?? [];
        $sync = [auth()->id() => ['creator' => true, 'active' => true]];
        foreach ($userIds as $userId) {
            $sync[$userId] = ['creator' => false, 'active' => true];
        }
        $notification->users()->sync($sync);

        return back()->with('success', __('ui.updated_success', ['item' => __('ui.notification')]));
    }

    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $this->authorize('delete', $notification);
        $notification->delete();

        return back()->with('success', __('ui.deleted_success', ['item' => __('ui.notification')]));
    }
}
