<div class="row-property mb-3 {{ $id ? '' : 'row-clone d-none' }}">

  <hr />

  <div class="row">

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

  </div>

</div>
