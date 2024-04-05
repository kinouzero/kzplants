<div class="form-check form-switch d-flex align-items-center {{ isset($class['parent']) ? $class['parent'] : '' }}"
  {!! isset($extra['parent']) ? $extra['parent'] : '' !!}>
  <input class="form-check-input {{ isset($class['input']) ? $class['input'] : '' }}" type="checkbox" role="switch"
    id="{{ $id }}" {{ $active ? 'checked' : '' }} />
  @include('template.form.label', [
      'id' => $id,
      'label' => $label,
      'class' => isset($class['label']) ? $class['label'] : null,
  ])
</div>
