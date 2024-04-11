<div class="form-floating {{ isset($class['parent']) ? $class['parent'] : '' }}"
  {{ isset($extra['parent']) ? $extra['parent'] : '' }}>
  @if ($type === 'select')
    @include('template.form.select', [
        'class' => isset($class['input']) ? $class['input'] : '',
        'id' => $id,
        'name' => $name,
        'options' => $options,
        'placeholder' => $placeholder,
        'extra' => isset($extra['input']) ? $extra['input'] : '',
    ])
  @elseif($type === 'textarea')
    @include('template.form.textarea', [
        'class' => isset($class['input']) ? $class['input'] : '',
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'extra' => isset($extra['input']) ? $extra['input'] : '',
    ])
  @elseif(in_array($type, ['text', 'email', 'password', 'datetime', 'number']))
    @include(sprintf('template.form.input.%s', $type), [
        'class' => isset($class['input']) ? $class['input'] : '',
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'extra' => isset($extra['input']) ? $extra['input'] : '',
    ])
  @endif
  @include('template.form.label', [
      'class' => isset($class['label']) ? $class['label'] : '',
      'id' => $id,
      'label' => $label,
  ])
</div>
