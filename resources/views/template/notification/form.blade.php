<form action="{{ $action }}" method="POST">
  @csrf

  @include('template.form.floating', [
      'type' => 'text',
      'id' => 'name',
      'name' => 'name',
      'label' => 'Name',
      'value' => $notification ? $notification->name : '',
      'class' => ['parent' => 'mb-3'],
      'extra' => ['input' => 'required autofocus'],
  ])

  @include('template.form.floating', [
      'type' => 'select',
      'id' => 'users',
      'name' => 'users[]',
      'label' => 'Users notified',
      'placeholder' => 'Select users',
      'options' => implode('', $options),
      'class' => ['parent' => 'mb-3', 'input' => 'select2'],
      'extra' => ['input' => 'multiple'],
  ])

  @include('template.form.floating', [
      'type' => 'textarea',
      'id' => 'description',
      'name' => 'description',
      'label' => 'Description',
      'value' => $notification ? $notification->description : '',
      'class' => ['parent' => 'mb-3'],
      'extra' => ['input' => 'style="height:8rem;"'],
  ])

  <hr />

  <div class="d-flex">
    <button class="btn btn-outline-success ms-auto" type="submit"><i class="far fa-save me-2"></i>Save</button>
  </div>

</form>
