@extends('template.app')

@section('content')
  {!! $style !!}

  <div class="card">
    <div class="card-body">

      @if (!$dashboard)
        <h1 class="text-center">
          <i class="{{ env('APP_ICON') }} me-2 fa-rotate-by" style="--fa-rotate-angle: -25deg;"></i>
          Welcome to {{ config('app.name') }}
        </h1>

        <hr />

        <p class="text-center mb-0">
          First, create a dashboard !<a href="{{ route('dashboard.create') }}" class="btn btn-outline-success ms-2">
            <i class="fas fa-table-columns me-2"></i>New dashboard
          </a>
        </p>
      @else
        <h1><i class="fas fa-table-columns fa-xs me-2"></i>{{ $dashboard->name }}</h1>

        <hr />

        <div class="row mb-3">
          <div class="col">
            <div class="card border-left-success mb-3 mb-md-0">
              <div class="card-body d-flex flex-wrap align-items-center">
                <span class="text-nowrap mb-2 mb-md-0">
                  <i class="fas fa-seedling me-2"></i>
                  {{ $strains->count() }} strain{{ $strains->count() > 1 ? 's' : '' }} in<i
                    class="fas fa-database ms-2"></i>
                </span>
                <a href="{{ route('plant.create') }}" class="btn btn-outline-success mx-auto me-md-0">
                  <i class="fas fa-seedling me-2"></i>
                  Add
                </a>
              </div>
            </div>
          </div>

          <div class="col">
            <div class="card border-left-primary">
              <div class="card-body d-flex flex-wrap align-items-center">
                <span class="text-nowrap mb-2 mb-md-0">
                  <i class="fas fa-cannabis me-2"></i>
                  {{ $plants->count() }} plant{{ $plants->count() > 1 ? 's' : '' }} in<i class="fas fa-database ms-2"></i>
                </span>
                <a href="{{ route('plant.create') }}" class="btn btn-outline-success mx-auto me-md-0">
                  <i class="fas fa-cannabis me-2"></i>
                  Add
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2">
          <div class="col mb-3 mb-lg-0">
            @include('template.dashboard.status')
          </div>
          <div class="col">
            @include('template.dashboard.water')
          </div>
        </div>
      @endif

    </div>
  </div>
@endsection
