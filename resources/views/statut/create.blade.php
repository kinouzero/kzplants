@extends('layouts.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="text-center">New statut</h1>

      <hr />

      @include('layouts.statut.form', ['action' => route('statut.store')])

    </div>
  </div>
@endsection
