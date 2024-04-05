<form action="{{ $action }}" method="POST">
  @csrf

  @include('template.form.floating', [
      'type' => 'text',
      'id' => 'name',
      'name' => 'name',
      'label' => 'Name',
      'value' => $item ? $item->name : '',
      'class' => ['parent' => 'mb-3'],
      'extra' => ['input' => 'required autofocus'],
  ])

  @include('template.form.floating', [
      'type' => 'select',
      'id' => 'checklist_id',
      'name' => 'checklist_id',
      'label' => 'Checklist',
      'placeholder' => 'Select checklist',
      'options' => implode('', $options_checklists),
      'class' => ['parent' => 'mb-3', 'input' => 'select2'],
      'extra' => ['input' => 'required autofocus'],
  ])

  @include('template.form.floating', [
      'type' => 'select',
      'id' => 'parent_id',
      'name' => 'parent_id',
      'label' => 'Parent',
      'placeholder' => 'Select parent',
      'options' => implode('', $options_parents),
      'class' => ['parent' => 'mb-3', 'input' => 'select2'],
      'extra' => null,
  ])

  <hr />

  <div class="d-flex">
    <button class="btn btn-outline-success ms-auto" type="submit"><i class="far fa-save me-2"></i>Save</button>
  </div>

  <script>
    function getItems(id) {
      const csrfToken = $('meta[name="csrf-token"]').attr('content');

      $.ajax({
        type: 'POST',
        url: '/checklist/' + id + '/items',
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        success: function(res) {
          $.each(res, function(index, data) {
            let option = new Option(data.name, data.id);
            $('#parent_id').append(option).trigger('change');
          });
        }
      });
    }
  </script>

</form>
