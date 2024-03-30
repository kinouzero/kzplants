@extends('layouts.app')

@section('content')
  {!! $style !!}

  <div class="card mx-auto">
    <div class="card-body pb-0">

      <div class="d-flex align-items-center">
        <h1 class="d-flex text-center align-items-center mb-0">
          <i class="fas fa-cannabis fa-2xs me-2"></i>
          {{ $plant->name }}
        </h1>
        <span class="border py-1 rounded ms-auto"
          style="border-color: {{ $plant->statut->color }}!important;color: {{ $plant->statut->color }};padding:0 3rem;">
          {{ $plant->statut->name }}
        </span>
      </div>

      <hr />

      <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3">

        <div class="col">

          <div class="card mb-3">
            <div class="card-body pb-0">

              <h4 class="d-flex align-items-center">
                <i class="fas fa-list-check fa-xs me-2"></i>
                <span>Checklists</span>
                <a class="btn btn-outline-secondary ms-auto" title="Add" data-bs-toggle="tooltip"
                  data-bs-placement="left" href="{{ route('plant.add.list', ['id' => $plant->id]) }}">
                  <i class="fas fa-plus"></i>
                </a>
              </h4>

              <hr />

              {!! $plant->templateChecklists() !!}

            </div>
          </div>

        </div>

        <div class="col">

          <div class="card mb-3">
            <div class="card-body">

              <h4><i class="far fa-square-poll-horizontal me-2"></i>Details</h4>

              <hr />

              <div class="card mb-3">
                <div class="card-body">

                  <h5 class="d-flex flex-nowrap align-items-center">
                    <i class="fas fa-tags fa-xs me-2"></i>
                    <span>Tags</span>
                    <a class="btn btn-outline-secondary ms-auto" title="Add" data-bs-toggle="tooltip"
                      data-bs-placement="left" href="{{ route('plant.edit', ['id' => $plant->id]) }}">
                      <i class="fas fa-plus"></i>
                    </a>
                  </h5>

                  <hr />

                  {!! $plant->templateTags() !!}

                </div>
              </div>

              <div class="card">
                <div class="card-body">

                  <h5 class="d-flex flex-nowrap align-items-center">
                    <i class="fas fa-sitemap fa-xs me-2"></i>
                    <span>Properties</span>
                    <a class="btn btn-outline-secondary ms-auto" title="Add" data-bs-toggle="tooltip"
                      data-bs-placement="left" href="{{ route('plant.edit', ['id' => $plant->id]) }}">
                      <i class="fas fa-plus"></i>
                    </a>
                  </h5>

                  <hr />

                  {!! $plant->templateProperties() !!}

                </div>
              </div>

            </div>
          </div>

        </div>

        <div class="col">

          <div class="card mb-3">
            <div class="card-body">

              <h4 class="d-flex align-items-center">
                <i class="fas fa-droplet fa-xs me-2"></i>
                <span>Water</span>
              </h4>

              <hr />

              <div class="d-flex flex-nowrap">
                <form id="water-wo-chem" action="{{ route('water.wo.chem', ['id' => $plant->id]) }}" method="POST">
                  @csrf
                </form>
                <a href="#" class="btn btn-outline-primary btn-form flex-fill me-1" data-form="#water-wo-chem"
                  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Without chemical">
                  <i class="fas fa-water"></i>
                </a>
                <form id="water-w-chem" action="{{ route('water.w.chem', ['id' => $plant->id]) }}" method="POST">
                  @csrf
                </form>
                <a href="#" class="btn btn-outline-danger btn-form flex-fill ms-1" data-form="#water-w-chem"
                  data-bs-toggle="tooltip" data-bs-placement="bottom" title="With chemical">
                  <i class="fas fa-biohazard"></i>
                </a>
              </div>

            </div>
          </div>

          <div class="card mb-3">
            <h4 class="card-header bg-white text-decoration-none d-flex flex-nowrap align-items-center collapsed"
              role="button" data-bs-toggle="collapse" data-bs-target="#timeline" aria-expanded="false"
              aria-controls="timeline">
              <i class="fas fa-timeline fa-90 fa-xs me-2"></i>
              <span>Timeline</span>
            </h4>

            <div class="card-body collapse" id="timeline">

              {!! $plant->templateTimeline() !!}

            </div>
          </div>

        </div>

      </div>

    </div>
  </div>
@endsection
