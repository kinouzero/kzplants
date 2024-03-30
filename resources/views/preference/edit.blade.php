@extends('layouts.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="text-center">Edit preference</h1>

      <hr />

      @include('layouts.preference.form', [
          'action' => route('preference.update', ['id' => $preference->id]),
      ])

    </div>
  </div>
@endsection
