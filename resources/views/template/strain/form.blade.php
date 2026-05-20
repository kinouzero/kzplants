<form action="{{ $action }}" method="POST">
  @csrf

  @include('template.form.floating', [
      'type' => 'text',
      'id' => 'name',
      'name' => 'name',
      'label' => __('ui.name'),
      'value' => $strain ? $strain->name : '',
      'class' => ['parent' => 'mb-3'],
      'extra' => ['input' => 'required autofocus'],
  ])

  @include('template.form.floating', [
      'type' => 'select',
      'id' => 'tags',
      'name' => 'tags[]',
      'label' => __('app.tags'),
      'placeholder' => __('ui.select_tags'),
      'options' => implode('', $options_tags),
      'class' => ['parent' => 'mb-3', 'input' => 'select2'],
      'extra' => ['input' => 'multiple'],
  ])

  <hr />

  <h3 class="text-center"><i class="fas fa-sitemap me-2"></i>{{ __('app.properties') }}</h3>

  <div id="strain-properties">

    {!! implode('', $template_properties) !!}

  </div>

  <hr />

  <div class="d-flex">
    <button class="btn btn-outline-secondary btn-add-row" type="button" data-row-container="#strain-properties">
      <i class="fas fa-sitemap me-2"></i>{{ __('ui.add_property') }}</button>
    <button class="btn btn-outline-success ms-auto" type="submit"><i class="far fa-save me-2"></i>{{ __('ui.save') }}</button>
  </div>
</form>
