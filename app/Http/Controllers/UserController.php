<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Preference;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{
    // Views
    public function index()
    {
        $this->authorize('viewAny', User::class);
        $users = User::all();

        return view('user.index', compact('users'));
    }

    public function create()
    {
        $this->authorize('create', User::class);
        $user = null;
        $roles = Role::all();
        $preferences = Preference::all();

        $title = __('ui.create_new', ['item' => __('ui.user')]);

        $template_preferences = [];
        foreach ($preferences as $preference) {

            $options_preferences = [];
            if (($options = $preference->options()) && array_key_exists('props', $options)) {
                foreach ($options['props'] as $k => $v) {
                    $options_preferences[] = view('template.form.select.option', ['value' => $k, 'title' => $v, 'selected' => false]);
                }
            }

            if ($preference->type === 'checklist') {
                $template_preferences[] = view('template.form.floating', [
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
            } else {
                $template_preferences[] = view('template.form.floating', [
                    'type' => $preference->type,
                    'id' => sprintf('preference-%s', $preference->id),
                    'name' => sprintf('preferences[%s]', $preference->id),
                    'label' => $preference->name,
                    'value' => '',
                    'class' => ['parent' => 'mb-3'],
                    'extra' => null,
                ]);
            }
        }

        return view('user.edit', compact('user', 'roles', 'template_preferences', 'title'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);
        $roles = Role::all();
        $preferences = Preference::all();

        $title = __('ui.edit_item', ['item' => __('ui.user'), 'name' => $user->name]);

        $template_preferences = [];
        foreach ($preferences as $preference) {
            $userPref = $user ? $user->preferences()->where('preference_id', $preference->id)->first() : null;

            $options_preferences = [];
            $userPrefValue = $userPref ? $userPref->pivot->value : null;
            $decoded = null;
            if ($userPrefValue && is_string($userPrefValue)) {
                $decoded = json_decode($userPrefValue, true);
            }
            if (($options = $preference->options()) && array_key_exists('props', $options)) {
                foreach ($options['props'] as $k => $v) {
                    $selected = false;
                    if (is_array($decoded)) {
                        $selected = in_array($k, $decoded, true);
                    } else {
                        $selected = $userPref && $userPref->pivot->value == $k;
                    }
                    $options_preferences[] = view('template.form.select.option', ['value' => $k, 'title' => $v, 'selected' => $selected]);
                }
            }

            if ($preference->type === 'checklist') {
                $template_preferences[] = view('template.form.floating', [
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
            } else {
                $template_preferences[] = view('template.form.floating', [
                    'type' => 'text',
                    'id' => sprintf('preference-%s', $preference->id),
                    'name' => sprintf('preferences[%s]', $preference->id),
                    'label' => $preference->name,
                    'value' => $userPref ? $userPref->pivot->value : '',
                    'class' => ['parent' => 'mb-3'],
                    'extra' => null,
                ]);
            }
        }

        return view('user.edit', compact('user', 'roles', 'preferences', 'title', 'template_preferences'));
    }

    public function detail($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('view', $user);

        return view('user.detail', compact('user'));
    }

    // Actions
    public function store(UserStoreRequest $request, UserService $userService)
    {
        $this->authorize('create', User::class);
        $userService->create($request->validated());

        return back()->with('success', __('ui.created_success', ['item' => __('ui.user')]));
    }

    public function update(UserUpdateRequest $request, $id, UserService $userService)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);
        $userService->update($user, $request->validated());

        return back()->with('success', __('ui.updated_success', ['item' => __('ui.user')]));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);
        $user->delete();

        return back()->with('success', __('ui.deleted_success', ['item' => __('ui.user')]));
    }
}
