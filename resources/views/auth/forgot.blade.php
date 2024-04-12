@extends('template.app')

@section('content')
  <div class="card mx-auto"
       style="width: 400px">
    <div class="card-body">

      <h1 class="d-flex align-items-center justify-content-center">
        <i class="far fa-envelope fa-xs me-3 mt-1"></i>
        <span>Forgot password</span>
      </h1>

      <hr />

      <form method="POST"
            action="{{ route('forgot.email') }}">
        @csrf

        @include('template.form.floating', [
            'type' => 'email',
            'id' => 'email',
            'name' => 'email',
            'label' => 'Email',
            'value' => old('email'),
            'class' => null,
            'extra' => ['input' => 'required autofocus'],
        ])

        <hr />

        <div class="d-flex">
          <button class="btn btn-outline-success ms-auto"
                  type="submit">
            <i class="far fa-paper-plane me-2"></i>Send link
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection
