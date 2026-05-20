@if ($plant->firstStage())
  <div class="row row-cols-1 accordion no-gutters">
    <div class="col mb-3 last-margin-0">
      {!! App\Presenters\PlantPresenter::templateChecklistTree($plant) !!}
    </div>
  </div>
@else
  @include('template.alert', [
      'color' => 'secondary',
      'class' => 'text-center mb-0',
      'content' => __('ui.no_first_stage_yet'),
  ])
@endif
