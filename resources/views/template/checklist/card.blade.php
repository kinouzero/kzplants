<div class="card">
  <div
    class="card-header d-flex flex-nowrap align-items-center text-decoration-none {{ $active ? '' : 'collapsed' }} {{ auth()->user()->getTheme() === 'light' ? 'bg-white' : '' }}"
    data-bs-toggle="collapse" data-bs-target="#checklist-items-{{ $checklist->id }}"
    aria-expanded="{{ $active ? 'true' : 'false' }}" role="button">
    @include(sprintf(
            'template.checklist.header.%s',
            $page = Route::current()->getName() === 'plant.detail' ? 'detail' : 'add'))
  </div>

  <div class="card-body collapse {{ $active ? 'show' : '' }}" id="checklist-items-{{ $checklist->id }}">

    {!! App\Models\Checklist::templateItemsTree($plant, $checklist, $page) !!}

  </div>
</div>
