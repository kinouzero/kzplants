@extends('template.app')

@section('content')
  <div class="card mx-auto" style="width: 320px">
    <div class="card-body">
      <h1 class="text-center">{{ __('app.forgot_password') }}</h1>

      <hr />

      @if (session('status'))
        <div class="alert alert-success">
          {{ session('status') }}
        </div>
      @endif

      <form method="POST" action="{{ route('password.email') }}">
        @csrf

        @include('template.form.floating', [
            'type' => 'email',
            'id' => 'email',
            'name' => 'email',
            'label' => __('ui.email'),
            'value' => old('email'),
            'class' => ['parent' => 'mb-3'],
            'extra' => ['input' => 'required autofocus'],
        ])

        <div class="d-flex">
          <button class="btn btn-outline-success ms-auto" type="submit">{{ __('app.send_reset_link') }}</button>
        </div>
      </form>

      <hr />

      <div class="d-grid">
        <a class="btn btn-outline-secondary" href="{{ route('login') }}">{{ __('app.back_to_login') }}</a>
      </div>
    </div>
  </div>
@endsection
