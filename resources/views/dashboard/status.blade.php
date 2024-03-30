<div class="card">
  <div class="card-body pb-0">

    <h2 class="text-center">Status<i class="fab fa-pagelines fa-2xs ms-2"></i></h2>

    <hr />

    @if ($plants->isEmpty())
      @include('layouts.alert', [
          'color' => 'secondary',
          'class' => 'text-center mb-0',
          'content' => 'No plant yet',
      ])
    @else
      <div style="card mb-3">
        <div class="card-body">
          <canvas id="status"></canvas>
        </div>
      </div>

      @foreach ($plants as $plant)
        <div class="card mb-3" style="border-left: .25rem solid {{ $plant->statut->color }}!important;">
          <div class="card-body">

            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between">

              <div class="d-flex flex-wrap align-items-center justify-content-center">

                <div class="d-flex flex-nowrap align-items-center justify-content-center">
                  <i class="fas fa-cannabis me-2"></i>
                  <a class="text-dark text-nowrap" href="{{ route('plant.detail', ['id' => $plant->id]) }}"
                    title="{{ $plant->strain->name }}" data-bs-toggle="tooltip">
                    {{ $plant->name }}
                  </a>
                </div>

                <span class="badge mx-2" style="background-color:{{ $plant->statut->color }}">
                  {{ $plant->statut->name }}
                </span>

              </div>

              <div class="d-flex flex-nowrap align-items-center justify-content-center">

                <div class="d-flex flex-nowrap">
                  <div class="text-secondary" data-bs-toggle="tooltip" title="Details">
                    <i class="fas fa-info-circle" data-bs-toggle="popover" data-bs-placement="bottom"
                      data-bs-trigger="hover" data-bs-content="{!! htmlentities($plant->templateDetails()) !!}"></i>
                  </div>

                  <div class="ms-3 text-secondary" data-bs-toggle="tooltip" title="Tags">
                    <i class="fas fa-tags" data-bs-toggle="popover" data-bs-placement="bottom" data-bs-trigger="hover"
                      data-bs-content="{!! htmlentities($plant->templateTags()) !!}"></i>
                  </div>

                  <div class="ms-3 text-secondary" data-bs-toggle="tooltip" title="Properties">
                    <i class="fas fa-sitemap" data-bs-toggle="popover" data-bs-placement="bottom"
                      data-bs-trigger="hover" data-bs-content="{!! htmlentities($plant->templateProperties()) !!}"></i>
                  </div>

                </div>

              </div>
            </div>

          </div>
        </div>
      @endforeach
    @endif

  </div>
</div>
