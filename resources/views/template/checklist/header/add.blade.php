<div class="d-flex align-items-center">
  @if ($active)
    <button type="button" class="btn btn-outline-{{ $initial ? 'primary' : 'secondary' }} btn-form me-2"
      data-bs-toggle="tooltip" title="{{ __('ui.first_stage') }}" data-form="#initial-{{ $stage->id }}">
      <i class="fas fa-sitemap"></i>
    </button>
    <form method="POST" id="initial-{{ $stage->id }}"
      action="{{ route(sprintf('plant.%s', $initial ? 'remove' : 'add'), ['id' => $plant->id, 'objectType' => 'initial', 'objectId' => $stage->id]) }}">
      @csrf
    </form>
  @endif
  <div class="form-check form-switch switch-form d-flex align-items-center mb-0"
    data-form="#checklist-{{ $stage->id }}">
    <input class="form-check-input" type="checkbox" role="switch" id="switch-checklist-{{ $stage->id }}"
      {{ $active ? 'checked' : '' }} />
    <label class="form-check-label ms-2 text-nowrap"
      for="switch-checklist-{{ $stage->id }}">{{ $checklist->name }}</label>
  </div>
  <form method="POST" id="checklist-{{ $stage->id }}"
    action="{{ route(sprintf('plant.%s', $active ? 'remove' : 'add'), ['id' => $plant->id, 'objectType' => 'stage', 'objectId' => $stage->id]) }}">
    @csrf
  </form>
</div>
