<form action="{{ $action }}" method="POST">
  @csrf

  <h4 class="text-center"><i class="fas fa-info-circle fa-xs me-2"></i>Informations</h4>

  <div class="form-floating mb-3">
    <input class="form-control" type="text" id="name" name="name" placeholder=" " required autofocus
      @if ($user) value="{{ $user->name }}" @endif />
    <label class="form-label" for="name">Name</label>
  </div>

  <div class="form-floating mb-3">
    <input class="form-control" type="email" id="email" name="email" placeholder=" " required
      @if ($user) value="{{ $user->email }}" @endif />
    <label class="form-label" for="email">Email</label>
  </div>

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

  <div class="form-floating mb-3">
    <input class="form-control" type="password" id="password" name="password" placeholder=" " />
    <label class="form-label" for="password">Password</label>
  </div>

  <div class="form-floating mb-3">
    <input class="form-control" type="password2" id="password2" name="password2" placeholder=" " />
    <label class="form-label" for="password2">Retype password</label>
  </div>

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
          <div class="form-floating mb-3">
            <input class="form-control" type="{{ $preference->type }}" id="preference-{{ $preference->id }}"
              name="preference_{{ $preference->id }}" placeholder=" "
              @if ($userPref) value="{{ $userPref->pivot->value }}" @endif />
            <label class="form-label" for="preference-{{ $preference->id }}">{{ $preference->name }}</label>
          </div>
        @break
      @endswitch
    @endforeach
  @endif

  <hr />

  <div class="d-flex">
    <button class="btn btn-outline-success ms-auto" type="submit"><i class="far fa-save me-2"></i>Save</button>
  </div>
</form>
