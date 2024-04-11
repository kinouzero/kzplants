<div class="card h-100">
  <div class="card-body pb-0">

    <h2 class="text-center"><i class="fab fa-pagelines fa-2xs me-2"></i>Status</h2>

    <hr />

    @if ($plants->isEmpty())
      @include('template.alert', [
          'color' => 'secondary',
          'class' => 'text-center',
          'content' => 'No plant yet',
      ])
    @else
      <div style="card mb-3">
        <div class="card-body">
          <canvas id="status" class="chart" data-url="{{ route('chart', ['type' => 'status']) }}" data-title="Status"
            data-empty-color="#000"></canvas>
        </div>
      </div>

      @foreach ($plants as $plant)
        <div class="card mb-3" style="border-left: .25rem solid {{ $plant->statut->color }}!important;">
          <div class="card-body">

            <div class="d-flex flex-wrap align-items-center">

              <div class="d-flex flex-wrap align-items-center">

                <div class="d-flex flex-nowrap align-items-center">
                  @if ($default = $plant->defaultPicture())
                    <a class="me-2" href="{{ route('picture.src', ['id' => $default->id]) }}"
                      data-lightbox="main-{{ $plant->id }}" data-title="{{ $default->name }}">
                      <img class="img-thumbnail p-0 rounded object-fit-cover"
                        src="{{ route('picture.src', ['id' => $default->id]) }}" alt="{{ $default->name }}"
                        style="width:50px;height:50px;" />
                    </a>
                  @else
                    <i class="fas fa-cannabis me-2"></i>
                  @endif
                  <a class="text-{{ auth()->user()->getTheme() === 'light' ? 'dark' : 'light' }} text-nowrap"
                    href="{{ route('plant.detail', ['id' => $plant->id]) }}" title="{{ $plant->strain->name }}"
                    data-bs-toggle="tooltip">
                    {{ $plant->name }}
                  </a>
                </div>

                <span class="badge mx-2" style="background-color:{{ $plant->statut->color }}">
                  {{ $plant->statut->name }}
                </span>

              </div>

              <div class="d-flex flex-nowrap align-items-center ms-auto">
                <div class="d-flex flex-nowrap">

                  @if ($plant->checklists->count() > 0)
                    <div class="text-secondary">
                      <i class="fas fa-info-circle" data-bs-toggle="popover" data-bs-placement="bottom"
                        data-bs-trigger="hover" data-bs-content="{!! htmlentities($plant->templateDetails()) !!}"></i>
                    </div>
                  @endif

                  <div class="ms-3 text-secondary">
                    <i class="fas fa-tags" data-bs-toggle="popover" data-bs-placement="bottom" data-bs-trigger="hover"
                      data-bs-content="{!! htmlentities($plant->templateTags()) !!}"></i>
                  </div>

                  <div class="ms-3 text-secondary">
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
