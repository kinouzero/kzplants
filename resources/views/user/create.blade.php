@extends('template.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="text-center">New user</h1>

      <hr />

      @include('template.user.form', ['action' => route('user.store')])

    </div>
  </div>
@endsection
