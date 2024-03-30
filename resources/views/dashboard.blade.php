@extends('layouts.app')

@section('content')
  {!! $style !!}

  <div class="card">
    <div class="card-body">

      <h1 class="text-center">Welcome to kzPlants</h1>

      <hr />

      <div class="row mb-3">
        <div class="col">
          <div class="card border-left-success mb-3 mb-md-0">
            <div class="card-body d-flex flex-wrap align-items-center">
              <span class="text-nowrap mb-2 mb-md-0">
                <i class="fas fa-seedling me-2"></i>
                {{ $strains->count() }} strain{{ $strains->count() > 1 ? 's' : '' }} in<i class="fas fa-database ms-2"></i>
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
          @include('dashboard.status')
        </div>
        <div class="col">
          @include('dashboard.water')
        </div>
      </div>

    </div>
  </div>
@endsection
