<form action="{{ $action }}" method="POST">
  @csrf

  <div class="form-floating mb-3">
    <input class="form-control" type="text" id="name" name="name" placeholder=" " required autofocus
      @if ($property) value="{{ $property->name }}" @endif>
    <label class="form-label" for="name">Name</label>
  </div>

  <hr />

  <div class="d-flex">
    <button class="btn btn-outline-success ms-auto" type="submit"><i class="far fa-save me-2"></i>Save</button>
  </div>
</form>
