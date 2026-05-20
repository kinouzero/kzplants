@extends('template.app')

@section('content')
  <div class="card">
    <div class="card-body">
      <h1 class="d-flex align-items-center">
        <i class="fas fa-calendar-day fa-xs me-2"></i>{{ __('app.today') }}
      </h1>

      <hr />

      <div class="row row-cols-1 row-cols-lg-2">
        <div class="col mb-3">
          <div class="card h-100">
            <div class="card-body">
              <h4><i class="fas fa-triangle-exclamation me-2"></i>{{ __('ui.overdue_tasks') }}</h4>
              <hr />
              @if ($overdue->isEmpty())
                @include('template.alert', ['color' => 'secondary', 'class' => 'mb-0', 'content' => __('ui.no_overdue_task')])
              @else
                <ul class="list-group">
                  @foreach ($overdue as $item)
                    <li class="list-group-item d-flex align-items-center">
                      <a class="text-decoration-none" href="{{ route('plant.detail', ['id' => $item->plant_id]) }}">
                        {{ $item->plant->name }}
                      </a>
                      <span class="ms-auto text-danger">{{ $item->name }}</span>
                    </li>
                  @endforeach
                </ul>
              @endif
            </div>
          </div>
        </div>

        <div class="col mb-3">
          <div class="card h-100">
            <div class="card-body">
              <h4><i class="fas fa-clock me-2"></i>{{ __('ui.due_soon') }}</h4>
              <hr />
              @if ($dueSoon->isEmpty())
                @include('template.alert', ['color' => 'secondary', 'class' => 'mb-0', 'content' => __('ui.no_task_due')])
              @else
                <ul class="list-group">
                  @foreach ($dueSoon as $item)
                    <li class="list-group-item d-flex align-items-center">
                      <a class="text-decoration-none" href="{{ route('plant.detail', ['id' => $item->plant_id]) }}">
                        {{ $item->plant->name }}
                      </a>
                      <span class="ms-auto">{{ $item->name }}</span>
                    </li>
                  @endforeach
                </ul>
              @endif
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection
