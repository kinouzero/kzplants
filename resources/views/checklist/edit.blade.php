@extends('template.app')

@section('content')
  <div class="card">
    <div class="card-body">

      <h1><i class="far fa-pencil-alt fa-2xs me-3"></i>{{ $title }}</h1>

      <hr />

      @include('template.checklist.form', [
          'action' => $checklist ? route('checklist.update', ['id' => $checklist->id]) : route('checklist.store'),
      ])

    </div>
  </div>
@endsection
