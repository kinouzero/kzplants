@extends('layouts.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="text-center">Edit strain</h1>

      <hr />

      @include('layouts.strain.form', ['action' => route('strain.update', ['id' => $strain->id])])

    </div>
  </div>
@endsection
