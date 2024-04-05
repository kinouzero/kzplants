@extends('template.app')

@section('content')
  <div class="card">
    <div class="card-body">

      <h1><i class="far fa-pencil-alt fa-2xs me-3"></i>{{ $title }}</h1>

      <hr />

      @include('template.statut.form', [
          'action' => $statut ? route('statut.update', ['id' => $statut->id]) : route('statut.store'),
      ])

    </div>
  </div>
@endsection
