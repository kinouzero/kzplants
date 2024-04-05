<div class="d-flex align-items-center">
  @if ($active)
    <button type="button" class="btn btn-outline-{{ $initial ? 'primary' : 'secondary' }} btn-form me-2"
      data-bs-toggle="tooltip" title="First checklist" data-form="#initial-{{ $checklist->id }}">
      <i class="fas fa-sitemap"></i>
    </button>
    <form method="POST" id="initial-{{ $checklist->id }}"
      action="{{ route(sprintf('plant.%s', $initial ? 'remove' : 'add'), ['id' => $plant->id, 'objectType' => 'initial', 'objectId' => $checklist->id]) }}">
      @csrf
    </form>
  @endif
  <div class="form-check form-switch switch-form d-flex align-items-center mb-0"
    data-form="#checklist-{{ $checklist->id }}">
    <input class="form-check-input" type="checkbox" role="switch" id="switch-checklist-{{ $checklist->id }}"
      {{ $active ? 'checked' : '' }} />
    <label class="form-check-label ms-2 text-nowrap"
      for="switch-checklist-{{ $checklist->id }}">{{ $checklist->name }}</label>
  </div>
  <form method="POST" id="checklist-{{ $checklist->id }}"
    action="{{ route(sprintf('plant.%s', $active ? 'remove' : 'add'), ['id' => $plant->id, 'objectType' => 'checklist', 'objectId' => $checklist->id]) }}">
    @csrf
  </form>
</div>
