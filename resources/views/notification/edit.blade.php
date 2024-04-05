@extends('template.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="text-center">Edit notification</h1>

      <hr />

      @include('template.notification.form', [
          'action' => route('notification.update', ['id' => $notification->id]),
      ])

    </div>
  </div>
@endsection
