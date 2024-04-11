<div class="card h-100">
  <div class="card-body pb-0">

    <h2 class="text-center"><i class="fas fa-droplet fa-2xs me-2"></i>Watering</h2>

    <hr />

    @if ($plants->isEmpty())
      @include('template.alert', [
          'color' => 'secondary',
          'class' => 'text-center',
          'content' => 'No watering yet',
      ])
    @else
      <div style="card mb-3">
        <div class="card-body">
          <canvas id="waterings" class="chart" data-url="{{ route('chart', ['type' => 'watering']) }}"
            data-title="Watering" data-empty-color="#000"></canvas>
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

                <p class="d-flex align-items-center mb-0 ms-3">
                  <i class="far fa-droplet"></i>
                  <i class="fas fa-arrow-right mx-2"></i>
                  <i class="fas fa-{{ !$plant->lastWateringChemical() ? 'biohazard text-danger' : 'water text-primary' }}"
                    data-bs-toggle="tooltip"
                    title="With{{ !$plant->lastWateringChemical() ? '' : 'out' }} chemical"></i>
                </p>

              </div>

              <div class="d-flex ms-auto">
                <div class="d-flex flex-nowrap">
                  <form id="water-wo-chem" action="{{ route('water', ['id' => $plant->id]) }}" method="POST">
                    @csrf
                  </form>
                  <a href="#"
                    class="btn btn-outline-{{ !$plant->nextWateringChemical() ? 'primary' : 'secondary' }} btn-form flex-fill me-1"
                    data-form="#water-wo-chem" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    title="Without chemical">
                    <i class="fas fa-water"></i>
                  </a>
                  <form id="water-w-chem" action="{{ route('water.chem', ['id' => $plant->id]) }}" method="POST">
                    @csrf
                  </form>
                  <a href="#"
                    class="btn btn-outline-{{ $plant->nextWateringChemical() ? 'danger' : 'secondary' }} btn-form flex-fill ms-1"
                    data-form="#water-w-chem" data-bs-toggle="tooltip" data-bs-placement="bottom" title="With chemical">
                    <i class="fas fa-biohazard"></i>
                  </a>
                </div>
              </div>

            </div>

          </div>
        </div>
      @endforeach
    @endif

  </div>
</div>
