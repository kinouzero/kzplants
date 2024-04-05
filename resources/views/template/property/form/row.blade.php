<div class="row row-property mb-3 {{ $id ? '' : 'row-clone d-none' }}">

  <div class="col">
    @include('template.form.floating', [
        'type' => 'select',
        'id' => sprintf('properties-%s', $id ?: 'uid'),
        'name' => sprintf('properties[%s]', $id ?: 'uid'),
        'label' => 'Property',
        'placeholder' => 'Select property',
        'options' => implode('', $options),
        'class' => $id ? ['input' => 'select2'] : null,
        'extra' => null,
    ])
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
