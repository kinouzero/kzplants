  @extends('template.app')

  @section('content')
    <div class="card">
      <div class="card-body pb-0">

        <h1 class="d-flex align-items-center justify-content-between">
          <a href="{{ route('plant.detail', ['id' => $plant->id]) }}" class="btn btn-outline-secondary" title="Back"
            data-bs-toggle="tooltip" data-bs-placement="right"><i class="fas fa-arrow-left"></i></a>
          <span>Add checklist</span>
          <a href="{{ route('checklist.create') }}" class="btn btn-outline-secondary" title="Create"
            data-bs-toggle="tooltip" data-bs-placement="left"><i class="fas fa-plus"></i></a>
        </h1>

        <hr />

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xxl-4">

          @foreach ($checklists as $checklist)
            <div class="col mb-3">
              @include('template.checklist.card', [
                  'checklist' => $checklist,
                  'active' => ($active = $plant->checklists ? $plant->checklists->find($checklist->id) : null),
                  'initial' => ($first = $plant->firstChecklist()) && $first->id === $checklist->id ? true : false,
              ])
            </div>
          @endforeach

        </div>

      </div>
    </div>
  @endsection
