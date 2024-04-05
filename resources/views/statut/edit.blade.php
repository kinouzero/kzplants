@extends('template.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="text-center">Edit statut</h1>

      <hr />

      @include('template.statut.form', ['action' => route('statut.update', ['id' => $statut->id])])

    </div>
  </div>
@endsection
