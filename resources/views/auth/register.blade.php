@extends('template.app')

@section('content')
  <div class="card mx-auto"
       style="width: 400px">
    <div class="card-body">

      <h1 class="text-center"><i class="fas fa-user fa-xs me-3"></i>Register</h1>

      <hr />

      <form method="POST"
            action="{{ route('register') }}">
        @csrf

        @include('template.form.floating', [
            'type' => 'text',
            'id' => 'name',
            'name' => 'name',
            'label' => '<i class="far fa-circle-user fa-xs me-2"></i>Name',
            'value' => '',
            'class' => ['parent' => 'mb-3'],
            'extra' => ['input' => 'required autofocus autocomplete="username"'],
        ])

        @include('template.form.floating', [
            'type' => 'email',
            'id' => 'email',
            'name' => 'email',
            'label' => '@ Email',
            'value' => '',
            'class' => ['parent' => 'mb-3'],
            'extra' => ['input' => 'required autofocus autocomplete="email"'],
        ])

        @include('template.form.floating', [
            'type' => 'password',
            'id' => 'password',
            'name' => 'password',
            'label' => '<i class="fas fa-key fa-xs me-2"></i>Password',
            'value' => '',
            'class' => ['parent' => 'mb-3'],
            'extra' => ['input' => 'required  autocomplete="new-password"'],
        ])

        @include('template.form.floating', [
            'type' => 'password',
            'id' => 'password',
            'name' => 'password_confirmation',
            'label' => '<i class="fas fa-key fa-xs me-2"></i>Confirm password',
            'value' => '',
            'class' => null,
            'extra' => ['input' => 'required  autocomplete="new-password"'],
        ])

        <hr />

        <div class="d-flex align-items-center">
          <span class="text-secondary small">Have an account? <a
               class="link-success link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
               href="{{ route('login') }}">Login</a></span>
          <button class="btn btn-outline-success ms-auto"
                  type="submit">
            <i class="far fa-check me-2"></i>Register
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection
