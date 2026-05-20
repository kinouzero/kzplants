@if ($plant)
  <form action="{{ $action }}" method="POST">
    @csrf

    @include('template.form.floating', [
        'type' => 'text',
        'id' => 'name',
        'name' => 'name',
        'label' => __('ui.name'),
        'value' => $plant ? $plant->name : '',
        'class' => ['parent' => 'mb-3'],
        'extra' => ['input' => 'required autofocus'],
    ])

    @include('template.form.floating', [
        'type' => 'select',
        'id' => 'strain_id',
        'name' => 'strain_id',
        'label' => __('ui.strain'),
        'placeholder' => __('ui.select_strain'),
        'options' => implode('', $options_strains),
        'class' => ['parent' => 'mb-3', 'input' => 'select2'],
        'extra' => ['input' => 'required'],
    ])

    @include('template.form.floating', [
        'type' => 'select',
        'id' => 'dashboards',
        'name' => 'dashboards[]',
        'label' => __('app.dashboards'),
        'placeholder' => __('ui.select_dashboards'),
        'options' => implode('', $options_dashboards),
        'class' => ['parent' => 'mb-3', 'input' => 'select2'],
        'extra' => ['input' => 'multiple required'],
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

    <div class="card mb-3">
      <div class="card-body last-margin-0">

        <h4 class="text-center"><i class="fas fa-cogs fa-xs me-2"></i>{{ __('ui.preferences') }}</h4>

        <hr />

        {!! implode('', $template_preferences ?? []) !!}

      </div>
    </div>

    <div class="card mb-3">
      <div class="card-body pb-0">

        <h3 class="text-center"><i class="fas fa-sitemap me-2"></i>{{ __('app.properties') }}</h3>

        <div id="plant-properties" class="row-list" data-empty-msg="{{ __('ui.no_property_yet') }}">

          <hr />

          {!! implode('', $template_properties) !!}

        </div>

      </div>
    </div>

    <hr />

    <div class="d-flex">
      <button class="btn btn-outline-secondary btn-add-row" type="button" data-row-container="#plant-properties">
        <i class="fas fa-sitemap me-2"></i>{{ __('ui.add_property') }}</button>
      <button class="btn btn-outline-success ms-auto" type="submit"><i class="far fa-save me-2"></i>{{ __('ui.save') }}</button>
    </div>
  </form>
@else
  <form action="{{ $action }}" method="POST" enctype="multipart/form-data" data-stepper>
    @csrf

    <div class="stepper-indicators">
      <button type="button" class="stepper-indicator active" data-step-indicator="1">1. {{ __('ui.step_basics') }}</button>
      <button type="button" class="stepper-indicator" data-step-indicator="2">2. {{ __('ui.step_preferences') }}</button>
      <button type="button" class="stepper-indicator" data-step-indicator="3">3. {{ __('ui.step_start_date') }}</button>
    </div>

    <div data-step-pane="1">
      <div class="mb-3 position-relative">
        <label class="form-label">{{ __('ui.external_strain_search') }}</label>
        <input type="text" class="form-control" placeholder="{{ __('ui.external_strain_placeholder') }}" data-external-strain-search />
        <div class="list-group external-plant-results d-none" data-external-strain-results></div>
      </div>

      <input type="hidden" name="external_strain_id" data-step-required />
      <input type="hidden" name="external_strain_name" />
      <input type="hidden" name="external_strain_image_url" />

      <div class="alert alert-secondary">
        {{ __('ui.selected_strain') }}: <strong data-external-strain-selected>—</strong>
      </div>

      <div class="mb-3">
        <img data-external-strain-image class="img-thumbnail d-none" alt="Strain preview" />
      </div>

      @include('template.form.floating', [
          'type' => 'text',
          'id' => 'name',
          'name' => 'name',
          'label' => __('ui.name'),
          'value' => '',
          'class' => ['parent' => 'mb-3'],
          'extra' => ['input' => 'required data-step-required'],
      ])

      <div class="mb-3">
        <label class="form-label">{{ __('ui.photo_choice') }}</label>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="photo_mode" id="photo_mode_api" value="api" checked>
          <label class="form-check-label" for="photo_mode_api">{{ __('ui.photo_default_api') }}</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="photo_mode" id="photo_mode_upload" value="upload">
          <label class="form-check-label" for="photo_mode_upload">{{ __('ui.photo_upload') }}</label>
        </div>
      </div>

      <div class="mb-3 d-none" data-photo-upload>
        @include('template.form.floating', [
            'type' => 'file',
            'id' => 'plant_photo',
            'name' => 'plant_photo',
            'label' => __('ui.photo_upload'),
            'value' => null,
            'class' => ['parent' => 'mb-3'],
            'extra' => ['input' => 'accept="image/*"'],
        ])
      </div>

      <div class="d-flex">
        <button class="btn btn-outline-success ms-auto" type="button" data-step-action="next">{{ __('ui.next') }}</button>
      </div>
    </div>

    <div class="d-none" data-step-pane="2">
      @include('template.form.floating', [
          'type' => 'select',
          'id' => 'dashboards',
          'name' => 'dashboards[]',
          'label' => __('app.dashboards'),
          'placeholder' => __('ui.select_dashboards'),
          'options' => implode('', $options_dashboards),
          'class' => ['parent' => 'mb-3', 'input' => 'select2'],
          'extra' => ['input' => 'multiple required'],
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

      <div class="card mb-3">
        <div class="card-body last-margin-0">

          <h4 class="text-center"><i class="fas fa-cogs fa-xs me-2"></i>{{ __('ui.preferences') }}</h4>

          <hr />

          {!! implode('', $template_preferences ?? []) !!}

        </div>
      </div>

      <div class="card mb-3">
        <div class="card-body pb-0">

          <h3 class="text-center"><i class="fas fa-sitemap me-2"></i>{{ __('app.properties') }}</h3>

          <div id="plant-properties" class="row-list" data-empty-msg="{{ __('ui.no_property_yet') }}">

            <hr />

            {!! implode('', $template_properties) !!}

          </div>

        </div>
      </div>

      <div class="d-flex">
        <button class="btn btn-outline-secondary btn-add-row" type="button" data-row-container="#plant-properties">
          <i class="fas fa-sitemap me-2"></i>{{ __('ui.add_property') }}</button>
        <div class="ms-auto">
          <button class="btn btn-outline-secondary me-2" type="button" data-step-action="prev">{{ __('ui.back') }}</button>
          <button class="btn btn-outline-success" type="button" data-step-action="next">{{ __('ui.next') }}</button>
        </div>
      </div>
    </div>

    <div class="d-none" data-step-pane="3">
      @include('template.form.floating', [
          'type' => 'date',
          'id' => 'start_date',
          'name' => 'start_date',
          'label' => __('ui.start_date'),
          'value' => '',
          'class' => ['parent' => 'mb-3'],
          'extra' => ['input' => 'required data-step-required'],
      ])

      <div class="d-flex">
        <button class="btn btn-outline-secondary" type="button" data-step-action="prev">{{ __('ui.back') }}</button>
        <button class="btn btn-outline-success ms-auto" type="submit"><i class="far fa-save me-2"></i>{{ __('ui.save') }}</button>
      </div>
    </div>
  </form>
@endif
