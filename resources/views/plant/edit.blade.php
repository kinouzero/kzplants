@extends('template.app')

@section('content')
  <div class="card">
    <div class="card-body">

      <h1><i class="far fa-pencil-alt fa-2xs me-3"></i>{{ $title }}</h1>

      <hr />

      @include('template.plant.form', [
          'action' => $plant ? route('plant.update', ['id' => $plant->id]) : route('plant.store'),
      ])

    </div>
  </div>
@endsection
