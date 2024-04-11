<div class="small d-flex align-items-center mb-1">
  <i class="far fa-circle-dot text-secondary me-2"></i>
  <span>{{ $item->name }}</span>
  <a href="#" class="btn-form ms-auto text-{{ $flush ? 'primary' : 'secondary' }}" data-bs-toggle="tooltip"
    title="{{ $flush ? 'Dea' : 'A' }}ctivate flush" data-bs-placement="left" data-form="#flush-{{ $item->id }}">
    <i class="fas fa-droplet"></i>
  </a>
  <form method="POST" id="flush-{{ $item->id }}"
    action="{{ route(sprintf('plant.%s', $flush ? 'remove' : 'add'), ['id' => $plant->id, 'objectType' => 'flush', 'objectId' => $item->id]) }}">
    @csrf
  </form>
</div>
