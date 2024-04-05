@extends('template.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="text-center">Edit user</h1>

      <hr />

      @include('template.user.form', ['action' => route('user.update', ['id' => $user->id])])
    </div>
  </div>
@endsection
