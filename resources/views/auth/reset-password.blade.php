@extends('template.app')

@section('content')
  <div class="card mx-auto" style="width: 320px">
    <div class="card-body">
      <h1 class="text-center">{{ __('app.reset_password') }}</h1>

      <hr />

      <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        @include('template.form.floating', [
            'type' => 'email',
            'id' => 'email',
            'name' => 'email',
            'label' => __('ui.email'),
            'value' => old('email', $email),
            'class' => ['parent' => 'mb-3'],
            'extra' => ['input' => 'required autofocus'],
        ])

        @include('template.form.floating', [
            'type' => 'password',
            'id' => 'password',
            'name' => 'password',
            'label' => __('ui.password'),
            'value' => '',
            'class' => ['parent' => 'mb-3'],
            'extra' => ['input' => 'required'],
        ])

        @include('template.form.floating', [
            'type' => 'password',
            'id' => 'password_confirmation',
            'name' => 'password_confirmation',
            'label' => __('ui.confirm_password'),
            'value' => '',
            'class' => ['parent' => 'mb-3'],
            'extra' => ['input' => 'required'],
        ])

        <div class="d-flex">
          <button class="btn btn-outline-success ms-auto" type="submit">{{ __('app.reset_password') }}</button>
        </div>
      </form>
    </div>
  </div>
@endsection
