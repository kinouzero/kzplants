<div class="last-margin-0">
  <p><i class="{{ $checklist->icon }} me-2"></i>{{ $checklist->name }}</p>

  <p><i class="far fa-clock me-2"></i>{{ $item->name }}</p>

  <p class="d-flex align-items-center">
    <i class="far fa-droplet"></i>
    <i class="fas fa-arrow-right mx-2"></i>
    <i class="fas fa-{{ !$chemical ? 'biohazard' : 'water' }}"></i>
  </p>
</div>
