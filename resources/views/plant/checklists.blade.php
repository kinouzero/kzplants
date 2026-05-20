  @extends('template.app')

  @section('content')
    <div class="card">
      <div class="card-body pb-0">

        <h1 class="d-flex align-items-center justify-content-between">
          <a href="{{ route('plant.detail', ['id' => $plant->id]) }}" class="btn btn-outline-secondary" title="{{ __('ui.back') }}"
            data-bs-toggle="tooltip" data-bs-placement="right"><i class="fas fa-arrow-left"></i></a>
          <span>{{ __('ui.add_stage') }}</span>
          <a href="{{ route('stage.create') }}" class="btn btn-outline-secondary" title="{{ __('ui.create') }}"
            data-bs-toggle="tooltip" data-bs-placement="left"><i class="fas fa-plus"></i></a>
        </h1>

        <hr />

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xxl-4">

          @foreach ($stages as $stage)
            @if ($stage->checklist)
              <div class="col mb-3">
                @include('template.checklist.card', [
                    'checklist' => $stage->checklist,
                    'stage' => $stage,
                    'active' => ($active = $plant->stages ? $plant->stages->find($stage->id) : null),
                    'initial' => ($first = $plant->firstStage()) && $first->id === $stage->id ? true : false,
                ])
              </div>
            @endif
          @endforeach

        </div>

      </div>
    </div>
  @endsection
