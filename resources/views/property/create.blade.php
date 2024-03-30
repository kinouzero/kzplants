@extends('layouts.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="text-center">New property</h1>

      <hr />

      @include('layouts.property.form', ['action' => route('property.store')])

    </div>
  </div>
@endsection
