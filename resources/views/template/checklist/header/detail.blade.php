<div class="card-title d-flex align-items-center mb-0 {{ $completed ? 'text-success' : '' }}">
  @if ($checklist->icon)
    <i class="{{ $checklist->icon }} me-2"></i>
  @endif
  <span class="{{ $completed ? 'text-decoration-line-through' : '' }}">{{ $checklist->name }}</span>
</div>
