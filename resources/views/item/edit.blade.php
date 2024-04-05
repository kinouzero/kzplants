@extends('template.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="text-center">Edit checklist item</h1>

      <hr />

      @include('template.item.form', ['action' => route('item.update', ['id' => $item->id])])

    </div>
  </div>
@endsection
