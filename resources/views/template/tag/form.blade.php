<form action="{{ $action }}" method="POST">
  @csrf

  @include('template.form.floating', [
      'type' => 'text',
      'id' => 'name',
      'name' => 'name',
      'label' => 'Name',
      'value' => $tag ? $tag->name : '',
      'class' => ['parent' => 'mb-3'],
      'extra' => ['input' => 'required autofocus'],
  ])

  @include('template.form.color', [
      'id' => 'color',
      'name' => 'color',
      'label' => 'Color',
      'color' => $tag ? $tag->color : '#000000',
  ])

  <hr />

  <div class="d-flex">
    <button class="btn btn-outline-success ms-auto" type="submit"><i class="far fa-save me-2"></i>Save</button>
  </div>
</form>
