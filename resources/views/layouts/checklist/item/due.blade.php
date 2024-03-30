<div class="d-flex flex-column">

  <form action="{{ route('item.save.due', ['id' => $plant->id]) }}" method="POST">
    @csrf

    <input type="hidden" name="item_id" value="{{ $item->id }}" />

    <div class="form-floating mb-2">
      <input class="form-control" type="datetime-local" id="due" name="due" placeholder=" "
        @if ($due) value="{{ $due }}" @endif />
      <label class="form-label" for="due">Due date</label>
    </div>

    <div class="d-flex flex-nowrap">

      <button class="btn btn-outline-success flex-fill me-1" type="submit">
        <i class="far fa-save"></i>
      </button>

      <a href="#" class="btn btn-outline-danger btn-form flex-fill ms-1"
        data-form="#remove-due-{{ $item->id }}">
        <i class="far fa-trash-alt"></i>
      </a>

    </div>

  </form>

  <form id="remove-due-{{ $item->id }}" action="{{ route('item.remove.due', ['id' => $plant->id]) }}"
    method="POST">
    @csrf

    <input type="hidden" name="checklist_id" value="{{ $checklist->id }}" />
    <input type="hidden" name="item_id" value="{{ $item->id }}" />
  </form>

</div>
