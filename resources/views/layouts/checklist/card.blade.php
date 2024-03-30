<div class="card">
  <a class="card-header d-flex flex-nowrap text-decoration-none {{ $active ? '' : 'collapsed' }} bg-white" href="#"
    data-bs-toggle="collapse" data-bs-target="#list-items-{{ $checklist->id }}"
    aria-expanded="{{ $active ? 'true' : 'false' }}">
    @include(sprintf(
            'layouts.checklist.header.%s',
            $page = Route::current()->getName() === 'plant.detail' ? 'detail' : 'add'))
  </a>

  <div class="card-body collapse {{ $active ? 'show' : '' }}" id="list-items-{{ $checklist->id }}">

    {!! App\Models\Checklist::templateItemsTree($plant, $checklist, $page) !!}

  </div>
</div>
