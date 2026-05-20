<form action="{{ $action }}" method="POST">
  @csrf

  @include('template.form.floating', [
      'type' => 'text',
      'id' => 'name',
      'name' => 'name',
      'label' => __('ui.name'),
      'value' => $notification ? $notification->name : '',
      'class' => ['parent' => 'mb-3'],
      'extra' => ['input' => 'required autofocus'],
  ])

  @include('template.form.floating', [
      'type' => 'select',
      'id' => 'users',
      'name' => 'users[]',
      'label' => __('ui.users_notified'),
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
      'value' => $notification ? $notification->description : '',
      'class' => ['parent' => 'mb-3'],
      'extra' => ['input' => 'style="height:8rem;"'],
  ])

  <div class="row g-3 mb-3">
    <div class="col-md-6">
      <label class="form-label">{{ __('ui.channels') }}</label>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="mail" id="channel-mail" name="channels[]"
          {{ in_array('mail', $config['channels'] ?? []) ? 'checked' : '' }}>
        <label class="form-check-label" for="channel-mail">Mail</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="ntfy" id="channel-ntfy" name="channels[]"
          {{ in_array('ntfy', $config['channels'] ?? []) ? 'checked' : '' }}>
        <label class="form-check-label" for="channel-ntfy">ntfy</label>
      </div>
    </div>
    <div class="col-md-6">
      @include('template.form.floating', [
          'type' => 'text',
          'id' => 'mail_subject',
          'name' => 'mail_subject',
          'label' => __('ui.mail_subject'),
          'value' => $config['mail_subject'] ?? '',
          'class' => ['parent' => 'mb-0'],
      ])
    </div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-md-6">
      @include('template.form.floating', [
          'type' => 'text',
          'id' => 'ntfy_url',
          'name' => 'ntfy_url',
          'label' => __('ui.ntfy_url'),
          'value' => $config['ntfy_url'] ?? '',
      ])
    </div>
    <div class="col-md-6">
      @include('template.form.floating', [
          'type' => 'text',
          'id' => 'ntfy_topic',
          'name' => 'ntfy_topic',
          'label' => __('ui.ntfy_topic'),
          'value' => $config['ntfy_topic'] ?? '',
      ])
    </div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-md-6">
      @include('template.form.floating', [
          'type' => 'text',
          'id' => 'ntfy_tags',
          'name' => 'ntfy_tags',
          'label' => __('ui.ntfy_tags'),
          'value' => $config['ntfy_tags'] ?? '',
      ])
    </div>
    <div class="col-md-6">
      @include('template.form.floating', [
          'type' => 'number',
          'id' => 'ntfy_priority',
          'name' => 'ntfy_priority',
          'label' => __('ui.ntfy_priority'),
          'value' => $config['ntfy_priority'] ?? '',
      ])
    </div>
  </div>

  <hr />

  <div class="d-flex">
    <button class="btn btn-outline-success ms-auto" type="submit"><i class="far fa-save me-2"></i>{{ __('ui.save') }}</button>
  </div>

</form>
