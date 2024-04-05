@extends('template.app')

@section('content')
  <div class="card mx-auto" style="width: 300px">
    <div class="card-body">

      <h1 class="text-center">Login</h1>

      <hr />

      <form method="POST" action="{{ route('login') }}">
        @csrf

        @include('template.form.floating', [
            'type' => 'email',
            'id' => 'email',
            'name' => 'email',
            'label' => 'Email',
            'value' => '',
            'class' => ['parent' => 'mb-3'],
            'extra' => ['input' => 'required autofocus'],
        ])

        @include('template.form.floating', [
            'type' => 'password',
            'id' => 'password',
            'name' => 'password',
            'label' => 'Password',
            'value' => '',
            'class' => null,
            'extra' => ['input' => 'required'],
        ])

        <hr />

        <div class="d-flex">
          <button class="btn btn-outline-success ms-auto" type="submit">Login</button>
        </div>
      </form>
    </div>
  </div>
@endsection
