@extends('template.app')

@section('content')
  <div class="card mx-auto"
       style="width: 400px">
    <div class="card-body">

      <h1 class="text-center"><i class="fas fa-rotate-left fa-xs me-3"></i>Reset password</h1>

      <hr />

      <form method="POST"
            action="{{ route('password.store') }}">
        @csrf

        <input name="token"
               type="hidden"
               value="{{ $request->route('token') }}">

        @include('template.form.floating', [
            'type' => 'email',
            'id' => 'email',
            'name' => 'email',
            'label' => 'Email',
            'value' => '',
            'class' => ['parent' => 'mb-3'],
            'extra' => ['input' => 'required autofocus autocomplete="email"'],
        ])

        @include('template.form.floating', [
            'type' => 'password',
            'id' => 'password',
            'name' => 'password',
            'label' => 'Password',
            'value' => '',
            'class' => ['parent' => 'mb-3'],
            'extra' => ['input' => 'required autocomplete="new-password"'],
        ])

        @include('template.form.floating', [
            'type' => 'password',
            'id' => 'password',
            'name' => 'password_confirmation',
            'label' => 'Confirm password',
            'value' => '',
            'class' => null,
            'extra' => ['input' => 'required autocomplete="new-password"'],
        ])

        <hr />

        <div class="d-flex">
          <button class="btn btn-outline-success ms-auto"
                  type="submit">
            <i class="fas fa-rotate-left me-2"></i>Reset password
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection
