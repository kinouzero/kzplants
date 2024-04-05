<form action="{{ $action }}" method="POST">
  @csrf

  <h4 class="text-center"><i class="fas fa-info-circle fa-xs me-2"></i>Informations</h4>

  @include('template.form.floating', [
      'type' => 'text',
      'id' => 'name',
      'name' => 'name',
      'label' => 'Name',
      'value' => $user ? $user->name : '',
      'class' => ['parent' => 'mb-3'],
      'extra' => ['input' => 'required autofocus'],
  ])

  @include('template.form.floating', [
      'type' => 'email',
      'id' => 'email',
      'name' => 'email',
      'label' => 'Email',
      'value' => $user ? $user->email : '',
      'class' => ['parent' => 'mb-3'],
      'extra' => ['input' => 'required'],
  ])

  @if (auth()->user()->isAdmin())
    <div class="form-floating mb-3">
      <select class="form-control select2" id="roles" name="roles[]" multiple required>
        @foreach ($roles as $role)
          <option value="{{ $role->id }}" {{ $user && $user->roles->contains('id', $role->id) ? 'selected' : '' }}>
            {{ $role->name }}
          </option>
        @endforeach
      </select>
      <label class="form-label" for="roles">Roles</label>
    </div>
  @endif

  <hr />

  <h4 class="text-center"><i class="fas fa-key fa-xs me-2"></i>Update password</h4>

  <hr class="w-50 mx-auto" />

  @include('template.form.floating', [
      'type' => 'password',
      'id' => 'password',
      'name' => 'password',
      'label' => 'Password',
      'value' => '',
      'class' => ['parent' => 'mb-3'],
      'extra' => null,
  ])

  @include('template.form.floating', [
      'type' => 'password',
      'id' => 'password2',
      'name' => 'password2',
      'label' => 'Confirm password',
      'value' => '',
      'class' => ['parent' => 'mb-3'],
      'extra' => null,
  ])

  @if ($preferences)
    <hr />

    <h4 class="text-center"><i class="fas fa-cogs fa-xs me-2"></i>Preferences</h4>

    <hr class="w-50 mx-auto" />

    @foreach ($preferences as $preference)
      @php
        $userPref = $user
            ? $user
                ->preferences()
                ->where('preference_id', $preference->id)
                ->first()
            : null;
        $options = json_decode($preference->options, 1);
      @endphp
      @switch($preference->type)
        @case($preference->type === 'checklist')
          <div class="form-floating mb-3">
            <select class="form-control select2" id="preference-{{ $preference->id }}"
              name="preferences[{{ $preference->id }}]{{ $options && array_key_exists('multiple', $options) ? '[]' : '' }}"
              {{ $options && array_key_exists('multiple', $options) ? 'multiple' : '' }}>
              <option value="">Select</option>
              @if ($options && array_key_exists('props', $options))
                @foreach ($options['props'] as $k => $v)
                  <option value="{{ $k }}" {{ $userPref && $userPref->pivot->value == $k ? 'selected' : '' }}>
                    {{ $v }}
                  </option>
                @endforeach
              @endif
            </select>
            <label class="form-label" for="preference-{{ $preference->id }}">{{ $preference->name }}</label>
          </div>
        @break

        @default
          @include('template.form.floating', [
              'type' => $preference->type,
              'id' => sprintf('preference-%s', $preference->id),
              'name' => sprintf('preference_%s', $preference->id),
              'label' => 'Name',
              'value' => $userPref ? $userPref->pivot->value : '',
              'class' => ['parent' => 'mb-3'],
              'extra' => null,
          ])
        @break
      @endswitch
    @endforeach
  @endif

  <hr />

  <div class="d-flex">
    <button class="btn btn-outline-success ms-auto" type="submit"><i class="far fa-save me-2"></i>Save</button>
  </div>
</form>
