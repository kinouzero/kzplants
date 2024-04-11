@extends('template.app')

@section('content')
  {!! $style !!}

  <div class="card">
    <div class="card-body">

      <h1 class="d-flex text-center align-items-center">
        <i class="fas fa-cannabis fa-2xs me-2"></i>Plants
        <div class="ms-auto d-flex align-items-center"
          @if ($strains->count() === 0) title="Create a strain to start" data-bs-toggle="tooltip" data-bs-placement="left" @endif>
          <a class="btn btn-outline-secondary {{ $strains->count() === 0 ? 'disabled' : '' }}"
            href="{{ route('plant.create') }}" title="Create" data-bs-toggle="tooltip" data-bs-placement="left"><i
              class="fas fa-plus"></i></a>
        </div>
      </h1>

      <hr />

      <table class="datatable w-100" data-page-length={{ App\Models\User::getUserTableLength(auth()->user()) }}>
        <thead>
          <tr>
            <th>Name</th>
            <th>Strain</th>
            <th>Dashboards</th>
            <th>Tags</th>
            <th>Properties</th>
            <th class="text-end">Actions</th>
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
                  {!! $plant->templateTags() !!}
                </td>
                <td>
                  {!! $plant->templateProperties() !!}
                </td>
                <td class="text-end">
                  <div class="btn-group">
                    <a class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Details"
                      href="{{ route('plant.detail', ['id' => $plant->id]) }}"><i class="fas fa-info-circle"></i></a>
                    <a class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Edit"
                      href="{{ route('plant.edit', ['id' => $plant->id]) }}"><i class="fas fa-pencil-alt"></i></a>
                    <a class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Checklists"
                      href="{{ route('plant.checklists', ['id' => $plant->id]) }}"><i class="fas fa-list-check"></i></a>
                    <a class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Pictures"
                      href="{{ route('plant.pictures', ['id' => $plant->id]) }}"><i class="far fa-images"></i></a>
                    <a class="btn btn-outline-danger btn-form" data-bs-toggle="tooltip" title="Delete" href="#"
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
