<form action="{{ $action }}" method="POST">
  @csrf

  <div class="card mb-3">
    <div class="card-body">

      <h4 class="text-center"><i class="fas fa-info-circle fa-xs me-2"></i>Informations</h4>

      <hr />

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
          'class' => auth()->user()->isAdmin() ? ['parent' => 'mb-3'] : null,
          'extra' => ['input' => 'required'],
      ])

      @if (auth()->user()->isAdmin())
        <div class="form-floating">
          <select class="form-control select2" id="roles" name="roles[]" multiple required>
            @foreach ($roles as $role)
              <option value="{{ $role->id }}"
                {{ $user && $user->roles->contains('id', $role->id) ? 'selected' : '' }}>
                {{ $role->name }}
              </option>
            @endforeach
          </select>
          <label class="form-label" for="roles">Roles</label>
        </div>
      @endif

    </div>
  </div>

  <div class="card mb-3">
    <div class="card-body">

      <h4 class="text-center"><i class="fas fa-key fa-xs me-2"></i>Update password</h4>

      <hr />

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
          'class' => null,
          'extra' => null,
      ])

    </div>
  </div>

  <div class="card mb-3">
    <div class="card-body last-margin-0">

      <h4 class="text-center"><i class="fas fa-cogs fa-xs me-2"></i>Preferences</h4>

      <hr />

      {!! implode('', $template_preferences) !!}

    </div>
  </div>

  <hr />

  <div class="d-flex">
    <button class="btn btn-outline-success ms-auto" type="submit"><i class="far fa-save me-2"></i>Save</button>
  </div>
</form>
