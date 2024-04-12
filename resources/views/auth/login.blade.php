@extends('template.app')

@section('content')
  <div class="card mx-auto"
       style="width: 300px">
    <div class="card-body">

      <h1 class="text-center"><i class="fas fa-lock fa-xs me-3"></i>Login</h1>

      <hr />

      <span class="text-secondary small">Doesn't have an account? <a
           class="link-success link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
           href="{{ route('register') }}">Register</a></span>

      <hr />

      <form method="POST"
            action="{{ route('login') }}">
        @csrf

        @include('template.form.floating', [
            'type' => 'email',
            'id' => 'email',
            'name' => 'email',
            'label' => '@ Email',
            'value' => '',
            'class' => ['parent' => 'mb-3'],
            'extra' => ['input' => 'required autofocus'],
        ])

        @include('template.form.floating', [
            'type' => 'password',
            'id' => 'password',
            'name' => 'password',
            'label' => '<i class="fas fa-key fa-xs me-2"></i>Password',
            'value' => '',
            'class' => ['parent' => 'mb-3'],
            'extra' => ['input' => 'required'],
        ])

        @include('template.form.switch', [
            'id' => 'remember-me',
            'label' => 'Remember me',
            'class' => ['label' => 'mb-0 ms-2'],
            'extra' => ['input' => 'name="remember_me"'],
            'active' => false,
        ])

        <hr />

        <div class="d-flex align-items-center">
          <a class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover small"
             href="{{ route('forgot') }}">Forgot your password?</a>
          <button class="btn btn-outline-success ms-auto"
                  type="submit">
            <i class="fas fa-arrow-right-to-bracket me-2"></i>Login
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection
