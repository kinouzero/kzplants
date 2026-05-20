@extends('template.app')

@section('content')
  {!! $style !!}

  <div class="card">
    <div class="card-body">

      <h1 class="d-flex text-center align-items-center">
        <i class="fas fa-cannabis fa-2xs me-2"></i>{{ __('app.plants') }}
        <div class="ms-auto d-flex align-items-center"
          @if ($strains->count() === 0) title="{{ __('ui.create_strain_to_start') }}" data-bs-toggle="tooltip" data-bs-placement="left" @endif>
          <a class="btn btn-outline-secondary {{ $strains->count() === 0 ? 'disabled' : '' }}"
            href="{{ route('plant.create') }}" title="{{ __('ui.create') }}" data-bs-toggle="tooltip" data-bs-placement="left"><i
              class="fas fa-plus"></i></a>
        </div>
      </h1>

      <hr />

      <table class="datatable w-100" data-page-length={{ App\Models\User::getUserTableLength(auth()->user()) }}>
        <thead>
          <tr>
            <th>{{ __('ui.name') }}</th>
            <th>{{ __('ui.strain') }}</th>
            <th>{{ __('app.dashboards') }}</th>
            <th>{{ __('app.tags') }}</th>
            <th>{{ __('app.properties') }}</th>
            <th class="text-end">{{ __('ui.actions') }}</th>
          </tr>
        </thead>
        @if ($plants)
          <tbody>
            @foreach ($plants as $plant)
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <span class="badge text-nowrap me-2" style="background-color:{{ $plant->statut->color }}">
                      {{ $plant->statut->name }}
                    </span>
                    <span class="text-nowrap">{{ $plant->name }}</span>
                  </div>
                </td>
                <td>
                  <div class="d-flex flex-nowrap">
                    <span class="text-nowrap me-2">{{ $plant->strain->name }}</span>
                  </div>
                </td>
                <td>
                  @foreach ($plant->dashboards()->get() as $dashboard)
                    <p class="text-nowrap mb-0">{{ $dashboard->name }}</span>
                  @endforeach
                </td>
                <td>
                  <x-plant-tags :plant="$plant" />
                </td>
                <td>
                  <x-plant-properties :plant="$plant" />
                </td>
                <td class="text-end">
                  <div class="btn-group">
                    <a class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="{{ __('ui.details') }}"
                      href="{{ route('plant.detail', ['id' => $plant->id]) }}"><i class="fas fa-info-circle"></i></a>
                    <a class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="{{ __('ui.edit') }}"
                      href="{{ route('plant.edit', ['id' => $plant->id]) }}"><i class="fas fa-pencil-alt"></i></a>
                    <a class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="{{ __('app.stages') }}"
                      href="{{ route('plant.stages', ['id' => $plant->id]) }}"><i class="fas fa-list-check"></i></a>
                    <a class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="{{ __('ui.pictures') }}"
                      href="{{ route('plant.pictures', ['id' => $plant->id]) }}"><i class="far fa-images"></i></a>
                    <a class="btn btn-outline-danger btn-form" data-bs-toggle="tooltip" title="{{ __('ui.delete') }}" href="#"
                      data-form="#delete-plant-{{ $plant->id }}"><i class="far fa-trash-alt"></i></a>
                  </div>
                  <form id="delete-plant-{{ $plant->id }}"
                    action="{{ route('plant.destroy', ['id' => $plant->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        @endif
      </table>
    </div>
  </div>
@endsection
