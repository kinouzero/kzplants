@extends('template.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="text-center">Edit property</h1>

      <hr />

      @include('template.property.form', ['action' => route('property.update', ['id' => $property->id])])
      property
    </div>
  </div>
@endsection
