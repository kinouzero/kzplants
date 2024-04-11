@extends('template.app')

@section('content')
  @include('template.picture.gallery', ['object' => $strain, 'class' => 'mb-3'])

  <div class="card">
    <div class="card-body pb-0">

      <h1><i class="far fa-file-image fa-xs me-2"></i>Upload</h1>

      <hr />

      <form action="{{ route('upload.pictures') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="strain_id" value="{{ $strain->id }}" />
        <div class="mb-3">
          @include('template.form.upload', ['id' => 'upload', 'name' => 'pictures[]'])
        </div>
      </form>

    </div>
  </div>
@endsection
