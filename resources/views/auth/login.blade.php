@extends('template.app')

@section('content')
  <div class="card mx-auto" style="width: 300px">
    <div class="card-body">

      <h1 class="text-center">{{ __('app.login') }}</h1>

      <hr />

      <form method="POST" action="{{ route('login') }}">
        @csrf

        @include('template.form.floating', [
            'type' => 'email',
            'id' => 'email',
            'name' => 'email',
          'label' => __('ui.email'),
            'value' => '',
            'class' => ['parent' => 'mb-3'],
            'extra' => ['input' => 'required autofocus'],
        ])

        @include('template.form.floating', [
            'type' => 'password',
            'id' => 'password',
            'name' => 'password',
          'label' => __('ui.password'),
            'value' => '',
            'class' => null,
            'extra' => ['input' => 'required'],
        ])

        <hr />

        <div class="d-flex">
          <button class="btn btn-outline-success ms-auto" type="submit">{{ __('app.login') }}</button>
        </div>
      </form>

      @if (config('oidc.enabled'))
        <hr />
        <div class="d-grid">
          <a class="btn btn-outline-primary" href="{{ route('login.oidc') }}">SSO</a>
        </div>
      @endif
    </div>
  </div>
@endsection
