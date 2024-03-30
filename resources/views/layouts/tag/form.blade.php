<form action="{{ $action }}" method="POST">
  @csrf

  <div class="row mb-3">
    <div class="col">
      <div class="form-floating">
        <input class="form-control" type="text" id="name" name="name" placeholder=" " required autofocus
          @if ($tag) value="{{ $tag->name }}" @endif>
        <label class="form-label" for="name">Name</label>
      </div>
    </div>
    <div class="col-1">
      <input class="form-control h-100" type="color" id="color" name="color" placeholder=" " required autofocus
        @if ($tag) value="{{ $tag->color }}" @endif>
    </div>
  </div>

  <hr />

  <div class="d-flex">
    <button class="btn btn-outline-success ms-auto" type="submit"><i class="far fa-save me-2"></i>Save</button>
  </div>
</form>
