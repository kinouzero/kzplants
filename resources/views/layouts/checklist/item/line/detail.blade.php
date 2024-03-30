<div class="small d-flex align-items-center mb-1">

  @if ($hours['checked'] || ($current && $current->id === $item->id))
    <form id="toggle-checked-{{ $item->id }}" action="{{ route('item.toggle.checked', ['id' => $plant->id]) }}"
      method="POST">
      @csrf

      <input type="hidden" name="item_id" value="{{ $item->id }}" />
      <input type="hidden" name="checked" value="{{ $hours['checked'] ?: 'false' }}" />
    </form>

    <a class="btn-form" href="#" data-form="#toggle-checked-{{ $item->id }}">
      <i
        class="far fa-{{ $hours['checked'] ? 'check' : 'circle-dot' }} text-{{ $hours['checked'] ? 'success' : 'secondary' }} me-2"></i>
    </a>
  @else
    <span>
      <i class="far fa-circle-dot text-secondary me-2"></i>
    </span>
  @endif

  <span
    class="text-secondary {{ $hours['checked'] ? 'text-decoration-line-through' : ($current && $current->id === $item->id ? 'fw-bold' : '') }}">{{ $item->name }}</span>

  <div class="d-flex align-items-center ms-auto">

    {!! $hours['due']
        ? sprintf(
            '<span class="badge bg-%s me-2" title="%s" data-bs-toggle="tooltip" data-bs-placement="left">%s</span>',
            $conditions['restMoreThan1Day'] || $hours['checked']
                ? 'success'
                : ($conditions['lessThan24h']
                    ? 'warning'
                    : 'danger'),
            $hours['due']->format('H:i'),
            $hours['due']->format('d/m/Y'),
        )
        : '' !!}

    <a href="#" class="text-secondary d-flex align-items-center" title="Set due date" data-bs-toggle="tooltip"
      data-bs-placement="left">
      <i class="far fa-calendar-alt" data-bs-toggle="popover" data-bs-trigger="click" data-bs-placement="bottom"
        data-bs-sanitize="false"
        data-bs-content='{{ view('layouts.checklist.item.due', ['plant' => $plant, 'checklist' => $checklist, 'item' => $item, 'due' => $hours['due']]) }}'></i>
    </a>

  </div>

</div>
