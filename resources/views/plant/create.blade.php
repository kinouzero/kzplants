@extends('layouts.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="text-center">New plant</h1>

      <hr />

      @include('layouts.plant.form', ['action' => route('plant.store')])

    </div>
  </div>
@endsection
