@extends('template.app')

@section('content')
  <div class="card">
    <div class="card-body">

      <h1><i class="far fa-pencil-alt fa-2xs me-3"></i>{{ $title }}</h1>

      <hr />

      @include('template.user.form', [
          'action' => $user ? route('user.update', ['id' => $user->id]) : route('user.store'),
      ])

    </div>
  </div>
@endsection
