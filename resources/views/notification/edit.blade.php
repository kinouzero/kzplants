@extends('template.app')

@section('content')
  <div class="card">
    <div class="card-body">

      <h1><i class="far fa-pencil-alt fa-2xs me-3"></i>{{ $title }}</h1>

      <hr />

      @include('template.notification.form', [
          'action' => $notification
              ? route('notification.update', ['id' => $notification->id])
              : route('notification.store'),
      ])

    </div>
  </div>
@endsection
