@extends('layouts.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="text-center">New checklist</h1>

      <hr />

      @include('layouts.checklist.form', ['action' => route('checklist.store')])

    </div>
  </div>
@endsection
