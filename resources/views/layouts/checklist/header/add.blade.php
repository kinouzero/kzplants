<div class="d-flex align-items-center">
  <div class="form-check form-switch switch-collapse" data-form="#plant-list-{{ $checklist->id }}"
    data-target="#list-items-{{ $checklist->id }}">
    <input class="form-check-input" type="checkbox" role="switch" id="switch-list-{{ $checklist->id }}"
      {{ $active ? 'checked' : '' }}>
    <label class="form-check-label" for="switch-list-{{ $checklist->id }}">{{ $checklist->name }}</label>
  </div>
  <a class="btn btn-outline-{{ $initial->id === $checklist->id ? 'primary' : 'secondary' }} btn-form ms-auto"
    data-bs-toggle="tooltip" title="First checklist" href="#"
    data-form="#set-initial-list-{{ $checklist->id }}"><i class="fas fa-sitemap"></i></a>
</div>
