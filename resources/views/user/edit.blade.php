@extends('layouts.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="text-center">Edit user</h1>

      <hr />

      @include('layouts.user.form', ['action' => route('user.update', ['id' => $user->id])])
    </div>
  </div>
@endsection
