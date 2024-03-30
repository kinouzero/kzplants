@extends('layouts.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="text-center">Edit checklist</h1>

      <hr />

      @include('layouts.checklist.form', ['action' => route('checklist.update', ['id' => $checklist->id])])

    </div>
  </div>
@endsection
