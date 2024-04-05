@extends('template.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="text-center">Edit checklist</h1>

      <hr />

      @include('template.checklist.form', [
          'action' => route('checklist.update', ['id' => $checklist->id]),
      ])

    </div>
  </div>
@endsection
