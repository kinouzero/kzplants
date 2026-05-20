<form action="{{ $action }}" method="POST">
  @csrf

  @include('template.form.floating', [
      'type' => 'text',
      'id' => 'name',
      'name' => 'name',
      'label' => __('ui.name'),
      'value' => $stage ? $stage->name : '',
      'class' => ['parent' => 'mb-3'],
      'extra' => ['input' => 'required autofocus'],
  ])

  @include('template.form.floating', [
      'type' => 'text',
      'id' => 'checklist_name',
      'name' => 'checklist_name',
      'label' => __('ui.checklist_name'),
      'value' => $stage && $stage->checklist ? $stage->checklist->name : '',
      'class' => ['parent' => 'mb-3'],
      'extra' => ['input' => 'required'],
  ])

  @include('template.form.floating', [
      'type' => 'text',
      'id' => 'checklist_icon',
      'name' => 'checklist_icon',
      'label' => __('ui.checklist_icon'),
      'value' => $stage && $stage->checklist ? $stage->checklist->icon : '',
      'class' => ['parent' => 'mb-3'],
      'extra' => null,
  ])

  @include('template.form.floating', [
      'type' => 'number',
      'id' => 'order',
      'name' => 'order',
      'label' => __('ui.order'),
      'value' => $stage ? $stage->order : $nextOrder,
      'class' => ['parent' => 'mb-3'],
      'extra' => ['input' => 'min="0"'],
  ])

  @include('template.form.floating', [
      'type' => 'number',
      'id' => 'interval_stage_days',
      'name' => 'interval_stage_days',
      'label' => __('ui.interval_stage_days'),
      'value' => $stage ? $stage->interval_stage_days : 0,
      'class' => ['parent' => 'mb-3'],
      'extra' => ['input' => 'min="0"'],
  ])

  @include('template.form.floating', [
      'type' => 'number',
      'id' => 'interval_item_days',
      'name' => 'interval_item_days',
      'label' => __('ui.interval_item_days'),
      'value' => $stage ? $stage->interval_item_days : 7,
      'class' => ['parent' => 'mb-3'],
      'extra' => ['input' => 'min="0"'],
  ])

  <hr />

  <div class="d-flex">
    <button class="btn btn-outline-success ms-auto" type="submit"><i class="far fa-save me-2"></i>{{ __('ui.save') }}</button>
  </div>

</form>
