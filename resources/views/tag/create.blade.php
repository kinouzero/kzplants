@extends('layouts.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="text-center">New tag</h1>

      <hr />

      @include('layouts.tag.form', ['action' => route('tag.store')])

    </div>
  </div>
@endsection
