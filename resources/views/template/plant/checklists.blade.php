@if ($plant->firstChecklist())
  <div class="row row-cols-1 accordion no-gutters">
    <div class="col mb-3 last-margin-0">
      {!! App\Models\Plant::templateChecklistTree($plant) !!}
    </div>
  </div>
@else
  @include('template.alert', [
      'color' => 'secondary',
      'class' => 'text-center mb-0',
      'content' => 'No first checklist yet',
  ])
@endif
