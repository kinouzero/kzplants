<div class="small d-flex align-items-center mb-1">

  @if ($hours['checked'] || $current)
    <form id="toggle-checked-{{ $item->id }}" action="{{ route('item.toggle', ['id' => $plant->id]) }}" method="POST">
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

  <span class="text-secondary {{ $hours['checked'] ? 'text-decoration-line-through' : ($current ? 'fw-bold' : '') }}">
    {{ $item->name }}
  </span>

  @if ($flush)
    <span class="ms-2 text-primary" data-bs-toggle="tooltip" title="Flush" data-bs-placement="right">
      <i class="fas fa-droplet"></i>
    </span>
  @endif

  <div class="d-flex align-items-center ms-auto">

    @if ($hours['checked'])
      <div class="d-flex align-items-center">
        <span class="badge bg-success" title="{{ $hours['checked']->format('H:i') }}" data-bs-toggle="tooltip"
          data-bs-placement="left">{{ $hours['checked']->format('d/m/Y') }}<i
            class="fas fa-calendar-check ms-2"></i></span>
      </div>
    @else
      <div class="d-flex align-items-center">
        <a href="#" class="text-secondary d-flex align-items-center" title="Set due date" data-bs-toggle="tooltip"
          data-bs-placement="top">
          @if ($hours['due'])
            <span
              class="badge bg-{{ $conditions['restMoreThan1Day'] ? 'success' : ($conditions['lessThan24h'] ? 'warning' : 'danger') }}"
              title="{{ $hours['due']->format('H:i') }}" data-bs-toggle="tooltip" data-bs-placement="left">
              {{ $hours['due']->format('d/m/Y') }}<i class="fas fa-calendar-day ms-2" data-bs-toggle="popover"
                data-bs-trigger="click" data-bs-placement="bottom" data-bs-sanitize="false"
                data-bs-content='{{ view('template.checklist.item.due', ['plant' => $plant, 'checklist' => $checklist, 'item' => $item, 'due' => $hours['due']]) }}'></i>
            </span>
          @else
            <i class="fas fa-calendar-day" data-bs-toggle="popover" data-bs-trigger="click" data-bs-placement="bottom"
              data-bs-sanitize="false"
              data-bs-content='{{ view('template.checklist.item.due', ['plant' => $plant, 'checklist' => $checklist, 'item' => $item, 'due' => $hours['due']]) }}'></i>
          @endif
        </a>
      </div>
    @endif
  </div>

</div>
