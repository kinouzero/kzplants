@extends('layouts.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="text-center">New strain</h1>

      <hr />

      @include('layouts.strain.form', ['action' => route('strain.store')])

    </div>
  </div>
@endsection
