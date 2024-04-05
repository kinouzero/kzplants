@extends('template.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="text-center">Edit plant</h1>

      <hr />

      @include('template.plant.form', ['action' => route('plant.update', ['id' => $plant->id])])

    </div>
  </div>
@endsection
