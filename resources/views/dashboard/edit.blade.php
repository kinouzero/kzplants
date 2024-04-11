@extends('template.app')

@section('content')
  <div class="card">
    <div class="card-body">

      <h1><i class="far fa-pencil-alt fa-2xs me-3"></i>{{ $title }}</h1>

      <hr />

      @include('template.dashboard.form', [
          'action' => $dashboard ? route('dashboard.update', ['id' => $dashboard->id]) : route('dashboard.store'),
      ])

    </div>
  </div>
@endsection
