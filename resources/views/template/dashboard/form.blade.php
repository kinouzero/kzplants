<form action="{{ $action }}" method="POST">
  @csrf

  @include('template.form.floating', [
      'type' => 'text',
      'id' => 'name',
      'name' => 'name',
      'label' => __('ui.name'),
      'value' => $dashboard ? $dashboard->name : '',
      'class' => ['parent' => 'mb-3'],
      'extra' => ['input' => 'required autofocus'],
  ])

  @include('template.form.floating', [
      'type' => 'select',
      'id' => 'users',
      'name' => 'users[]',
      'label' => __('ui.users_visibility'),
      'placeholder' => __('ui.select_users'),
      'options' => implode('', $options),
      'class' => ['parent' => 'mb-3', 'input' => 'select2'],
      'extra' => ['input' => 'multiple'],
  ])

  @include('template.form.floating', [
      'type' => 'textarea',
      'id' => 'description',
      'name' => 'description',
      'label' => __('ui.description'),
      'value' => $dashboard ? $dashboard->description : '',
      'class' => ['parent' => 'mb-3'],
      'extra' => ['input' => 'style="height:8rem;"'],
  ])

  @include('template.form.color', [
      'id' => 'color',
      'name' => 'color',
      'label' => __('ui.color'),
      'color' => $dashboard ? $dashboard->color : '#000000',
  ])

  <hr />

  <div class="d-flex">
    <button class="btn btn-outline-success ms-auto" type="submit"><i class="far fa-save me-2"></i>{{ __('ui.save') }}</button>
  </div>

</form>
