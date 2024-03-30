@extends('layouts.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="text-center">New user</h1>

      <hr />

      @include('layouts.user.form', ['action' => route('user.store')])

    </div>
  </div>
@endsection
