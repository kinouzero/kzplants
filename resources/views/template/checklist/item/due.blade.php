<div class="d-flex flex-column">

  <form action="{{ route('item.due.save', ['id' => $plant->id]) }}" method="POST">
    @csrf

    <input type="hidden" name="item_id" value="{{ $item->id }}" />

    @include('template.form.floating', [
        'type' => 'datetime',
        'id' => 'due',
        'name' => 'due',
        'label' => 'Due date',
        'value' => $due,
        'class' => ['parent' => 'mb-2'],
        'extra' => null,
    ])

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

  <form id="remove-due-{{ $item->id }}" action="{{ route('item.due.remove', ['id' => $plant->id]) }}"
    method="POST">
    @csrf

    <input type="hidden" name="checklist_id" value="{{ $checklist->id }}" />
    <input type="hidden" name="item_id" value="{{ $item->id }}" />
  </form>

</div>
