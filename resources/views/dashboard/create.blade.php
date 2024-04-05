@extends('template.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="text-center">New dashboard</h1>

      <hr />

      @include('template.dashboard.form', ['action' => route('dashboard.store')])

    </div>
  </div>
@endsection
