@extends('template.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="text-center">New tag</h1>

      <hr />

      @include('template.tag.form', ['action' => route('tag.store')])

    </div>
  </div>
@endsection
