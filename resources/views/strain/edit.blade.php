@extends('template.app')

@section('content')
  <div class="card">
    <div class="card-body">

      <h1><i class="far fa-pencil-alt fa-2xs me-3"></i>{{ $title }}</h1>

      <hr />

      @include('template.strain.form', [
          'action' => $strain ? route('strain.update', ['id' => $strain->id]) : route('strain.store'),
      ])

    </div>
  </div>
@endsection
