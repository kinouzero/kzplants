<form action="{{ $action }}" method="POST">
  @csrf

  @include('template.form.floating', [
      'type' => 'text',
      'id' => 'name',
      'name' => 'name',
      'label' => 'Name',
      'value' => $preference ? $preference->name : '',
      'class' => ['parent' => 'mb-3'],
      'extra' => ['input' => 'required autofocus'],
  ])

  @include('template.form.floating', [
      'type' => 'text',
      'id' => 'key',
      'name' => 'key',
      'label' => 'Key',
      'value' => $preference ? $preference->key : '',
      'class' => ['parent' => 'mb-3'],
      'extra' => ['input' => 'required'],
  ])

  @include('template.form.floating', [
      'type' => 'select',
      'id' => 'type',
      'name' => 'type',
      'label' => 'Type',
      'options' => implode('', $types),
      'placeholder' => 'Select type',
      'class' => ['parent' => 'mb-3', 'input' => 'select2 change-type'],
      'extra' => [
          'input' => sprintf(
              'required data-card="#card-%s" data-btn="#btn-%s"',
              $preference ? $preference->id : '0',
              $preference ? $preference->id : '0'),
      ],
  ])

  @include('template.form.floating', [
      'type' => 'textarea',
      'id' => 'description',
      'name' => 'description',
      'label' => 'Description',
      'value' => $preference ? $preference->description : '',
      'class' => ['parent' => 'mb-3'],
      'extra' => ['input' => 'style="height:8rem"'],
  ])

  <div class="card mb-3 {{ $preference && $preference->type === 'select' ? '' : 'd-none' }}"
    id="card-type-{{ $preference ? $preference->id : '0' }}">
    <div class="card-body pb-0">

      <h3 class="text-center"><i class="fas fa-sitemap me-2"></i>Options</h3>

      <div id="preference-options" class="row-list" data-empty-msg="No option yet">

        <hr />

        {!! implode('', $template_options) !!}

      </div>

    </div>
  </div>

  <hr />

  <div class="d-flex">
    <button id="btn-{{ $preference ? $preference->id : '0' }}" class="btn btn-outline-secondary btn-add-row"
      type="button" data-row-container="#preference-options">
      <i class="fas fa-list-ul me-2"></i>Add preference</button>
    <button class="btn btn-outline-success ms-auto" type="submit"><i class="far fa-save me-2"></i>Save</button>
  </div>
</form>
