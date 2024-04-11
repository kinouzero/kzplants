<div class="row row-{{ $key }} mb-3 {{ $id ? '' : 'row-clone d-none' }}">

  <div class="col">
    @if ($type === 'select')
      @include('template.form.floating', [
          'type' => 'select',
          'id' => sprintf('%s-%s', $key, $id ?: 'uid'),
          'name' => sprintf('%s[%s]', $key, $id ?: 'uid'),
          'label' => $label,
          'placeholder' => sprintf('Select %s', strotolower($label)),
          'options' => implode('', $options),
          'class' => $id ? ['input' => 'select2'] : null,
          'extra' => null,
      ])
    @elseif($type === 'text')
      @include('template.form.floating', [
          'type' => 'text',
          'id' => sprintf('%s-%s', $key, $id ?: 'uid'),
          'name' => sprintf('%s[%s]', $key, $id ?: 'uid'),
          'label' => $label,
          'value' => $key_value,
          'class' => null,
          'extra' => null,
      ])
    @endif
  </div>

  <div class="col">
    @include('template.form.floating', [
        'type' => 'text',
        'id' => sprintf('values-%s', $id ?: 'uid'),
        'name' => sprintf('values[%s]', $id ?: 'uid'),
        'label' => 'Value',
        'value' => $value,
        'class' => null,
        'extra' => null,
    ])
  </div>

  <div class="col-auto d-flex align-items-center justify-content-end">
    <button type="button" class="btn btn-outline-danger btn-remove-row">
      <i class="far fa-trash-alt"></i>
    </button>
  </div>

</div>
