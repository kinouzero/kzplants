<form action="{{ $action }}" method="POST">
  @csrf

  <div class="form-floating mb-3">
    <input class="form-control" type="text" id="name" name="name" placeholder=" " required autofocus
      @if ($checklist) value="{{ $checklist->name }}" @endif>
    <label class="form-label" for="name">Name</label>
  </div>

  <div class="form-floating mb-3">
    <input class="form-control" type="text" id="icon" name="icon" placeholder=" "
      @if ($checklist) value="{{ $checklist->icon }}" @endif>
    <label class="form-label" for="icon">Icon</label>
  </div>

  <div class="form-floating mb-3">
    <select class="form-control select2" id="parents" name="parents[]" multiple>
      @foreach ($checklists as $_checklist)
        <option value="{{ $cl->id }}"
          {{ $checklist && $checklist->parents->contains('id', $_checklist->id) ? 'selected' : '' }}>
          {{ $_checklist->name }}
        </option>
      @endforeach
    </select>
    <label class="form-label" for="parents">Parents</label>
  </div>

  <div class="form-floating mb-3">
    <select class="form-control select2" id="items" name="items[]" multiple required>
      @foreach ($items as $item)
        <option value="{{ $item->id }}"
          {{ $checklist && $checklist->items->contains('id', $item->id) ? 'selected' : '' }}>
          {{ $item->name }}
        </option>
      @endforeach
    </select>
    <label class="form-label" for="items">Items</label>
  </div>

  <hr />

  <div class="d-flex">
    <button class="btn btn-outline-success ms-auto" type="submit"><i class="far fa-save me-2"></i>Save</button>
  </div>

</form>
