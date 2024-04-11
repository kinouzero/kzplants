<form action="{{ $action }}" method="POST">
  @csrf

  @include('template.form.floating', [
      'type' => 'text',
      'id' => 'name',
      'name' => 'name',
      'label' => 'Name',
      'value' => $checklist ? $checklist->name : '',
      'class' => ['parent' => 'mb-3'],
      'extra' => ['input' => 'required autofocus'],
  ])

  @include('template.form.icon', [
      'id' => 'icon',
      'name' => 'icon',
      'label' => 'Icon',
      'value' => $checklist ? $checklist->icon : '',
      'class' => ['row' => 'mb-3', 'parent' => 'flex-fill'],
      'extra' => ['input' => 'required'],
  ])

  @include('template.form.floating', [
      'type' => 'select',
      'id' => 'parents',
      'name' => 'parents[]',
      'label' => 'Parents',
      'placeholder' => 'Select parent',
      'options' => implode('', $options),
      'class' => ['parent' => 'mb-3', 'input' => 'select2'],
      'extra' => ['input' => 'multiple'],
  ])

  <hr />

  <div class="d-flex">
    <button class="btn btn-outline-success ms-auto" type="submit"><i class="far fa-save me-2"></i>Save</button>
  </div>

</form>
