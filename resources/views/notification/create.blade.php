@extends('template.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="text-center">New notification</h1>

      <hr />

      @include('template.notification.form', ['action' => route('notification.store')])

    </div>
  </div>
@endsection
