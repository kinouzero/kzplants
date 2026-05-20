<form action="{{ $action }}" method="POST">
  @csrf

  @include('template.form.floating', [
      'type' => 'text',
      'id' => 'name',
      'name' => 'name',
      'label' => __('ui.name'),
      'value' => $statut ? $statut->name : '',
      'class' => ['parent' => 'mb-3'],
      'extra' => ['input' => 'required autofocus'],
  ])

  @include('template.form.color', [
      'id' => 'color',
      'name' => 'color',
      'label' => __('ui.color'),
      'color' => $statut ? $statut->color : '#000000',
  ])

  <hr />

  <div class="d-flex">
    <button class="btn btn-outline-success ms-auto" type="submit"><i class="far fa-save me-2"></i>{{ __('ui.save') }}</button>
  </div>
</form>
