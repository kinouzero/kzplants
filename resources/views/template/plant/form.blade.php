<form action="{{ $action }}" method="POST">
  @csrf

  @include('template.form.floating', [
      'type' => 'text',
      'id' => 'name',
      'name' => 'name',
      'label' => 'Name',
      'value' => $plant ? $plant->name : '',
      'class' => ['parent' => 'mb-3'],
      'extra' => ['input' => 'required autofocus'],
  ])

  @include('template.form.floating', [
      'type' => 'select',
      'id' => 'strain_id',
      'name' => 'strain_id',
      'label' => 'Strain',
      'placeholder' => 'Select strain',
      'options' => implode('', $options_strains),
      'class' => ['parent' => 'mb-3', 'input' => 'select2'],
      'extra' => ['input' => 'required'],
  ])

  @include('template.form.floating', [
      'type' => 'select',
      'id' => 'dashboards',
      'name' => 'dashboards[]',
      'label' => 'Dashboards',
      'placeholder' => 'Select dashboards',
      'options' => implode('', $options_dashboards),
      'class' => ['parent' => 'mb-3', 'input' => 'select2'],
      'extra' => ['input' => 'multiple required'],
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

  <div class="card mb-3">
    <div class="card-body pb-0">

      <h3 class="text-center"><i class="fas fa-sitemap me-2"></i>Properties</h3>

      <div id="plant-properties" class="row-list" data-empty-msg="No property yet">

        <hr />

        {!! implode('', $template_properties) !!}

      </div>

    </div>
  </div>

  <hr />

  <div class="d-flex">
    <button class="btn btn-outline-secondary btn-add-row" type="button" data-row-container="#plant-properties">
      <i class="fas fa-sitemap me-2"></i>Add property</button>
    <button class="btn btn-outline-success ms-auto" type="submit"><i class="far fa-save me-2"></i>Save</button>
  </div>
</form>
