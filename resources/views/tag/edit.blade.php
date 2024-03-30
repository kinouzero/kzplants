@extends('layouts.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="text-center">Edit tag</h1>

      <hr />

      @include('layouts.tag.form', ['action' => route('tag.update', ['id' => $tag->id])])

    </div>
  </div>
@endsection
