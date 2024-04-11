<form action="{{ $action }}" method="POST">
  @csrf

  @include('template.form.floating', [
      'type' => 'text',
      'id' => 'name',
      'name' => 'name',
      'label' => 'Name',
      'value' => $strain ? $strain->name : '',
      'class' => ['parent' => 'mb-3'],
      'extra' => ['input' => 'required autofocus'],
  ])

  @include('template.form.floating', [
      'type' => 'select',
      'id' => 'tags',
      'name' => 'tags[]',
      'label' => 'Tags',
      'placeholder' => 'Select tags',
      'options' => implode('', $option_tags),
      'class' => ['parent' => 'mb-3', 'input' => 'select2'],
      'extra' => ['input' => 'multiple'],
  ])

  <hr />

  <h3 class="text-center"><i class="fas fa-sitemap me-2"></i>Properties</h3>

  <div id="strain-properties">

    {!! implode('', $template_properties) !!}

  </div>

  <hr />

  <div class="d-flex">
    <button class="btn btn-outline-secondary btn-add-row" type="button" data-row-container="#strain-properties">
      <i class="fas fa-sitemap me-2"></i>Add property</button>
    <button class="btn btn-outline-success ms-auto" type="submit"><i class="far fa-save me-2"></i>Save</button>
  </div>
</form>
