<div class="d-flex align-items-center position-relative input-icon {{ isset($class['row']) ? $class['row'] : '' }}">
  @include('template.form.floating', [
      'type' => 'text',
      'class' => $class,
      'id' => $id,
      'name' => $name,
      'value' => $value,
      'extra' => $extra,
  ])
  <div class="rounded border ms-3 d-flex align-items-center justify-content-center" style="width:58px;height:58px">
    <i class="{{ $value }}"></i>
  </div>
</div>
