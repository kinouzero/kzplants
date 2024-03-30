<div class="card border-left-info">
  <div class="card-body">

    <h2 class="text-center">Watering<i class="fas fa-droplet fa-2xs ms-2"></i></h2>

    <hr />

    @if ($plants->count() > 0)
      <canvas id="waterings"></canvas>
    @else
      @include('layouts.alert', [
          'color' => 'secondary',
          'class' => 'text-center mb-0',
          'content' => 'No watering yet',
      ])
    @endif
  </div>
</div>
