<form action="{{ $action }}" method="POST">
  @csrf

  <div class="form-floating mb-3">
    <input class="form-control" type="text" id="name" name="name" placeholder=" " required autofocus
      @if ($item) value="{{ $item->name }}" @endif>
    <label class="form-label" for="name">Name</label>
  </div>

  <div class="form-floating mb-3">
    <select class="form-control select2" id="checklist_id" name="checklist_id" required autofocus
      onchange="getItems($(this).val())">
      <option value="">Select</option>
      @foreach ($checklists as $checklist)
        <option value="{{ $checklist->id }}" {{ $item && $item->checklist->id === $checklist->id ? 'selected' : '' }}>
          {{ $checklist->name }}
        </option>
      @endforeach
    </select>
    <label class="form-label" for="checklist_id">Checklist</label>
  </div>

  <div class="form-floating mb-3">
    <select class="form-control select2" id="parent_id" name="parent_id">
      <option value="">Select</option>
      @foreach ($item->checklist->items as $_item)
        @if ($item && ($item->id === $_item->id || ($_item->child && $item->id !== $_item->child->id)))
          @continue;
        @endif
        <option value="{{ $_item->id }}" {{ $item->parent && $item->parent->id === $_item->id ? 'selected' : '' }}>
          {{ $_item->name }}
        </option>
      @endforeach
    </select>
    <label class="form-label" for="parent_id">Parent</label>
  </div>

  <hr />

  <div class="d-flex">
    <button class="btn btn-outline-success ms-auto" type="submit"><i class="far fa-save me-2"></i>Save</button>
  </div>

</form>
