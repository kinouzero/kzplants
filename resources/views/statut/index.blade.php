@extends('template.app')

@section('content')
  <div class="card">
    <div class="card-body">

      <h1 class="d-flex text-center align-items-center">
        <i class="fas fa-list-ul fa-2xs me-2"></i>Status
        <div class="ms-auto d-flex align-items-center">
          <a class="btn btn-outline-secondary" href="{{ route('statut.create') }}" title="Create" data-bs-toggle="tooltip"
            data-bs-placement="left"><i class="fas fa-plus"></i></a>
        </div>
      </h1>

      <hr />

      <table class="datatable w-100" data-page-length={{ App\Models\User::getUserTableLength(auth()->user()) }}>
        <thead>
          <tr>
            <th>Name</th>
            <th>Color</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        @if ($status)
          <tbody>
            @foreach ($status as $statut)
              <tr>
                <td>{{ $statut->name }}</td>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="rounded me-2" style="height:30px;width:30px;background-color:{{ $statut->color }}"></div>
                    <span>{{ $statut->color }}</span>
                  </div>
                </td>
                <td class="text-end">
                  <div class="btn-group">
                    <a class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Edit"
                      href="{{ route('statut.edit', ['id' => $statut->id]) }}"><i class="fas fa-pencil-alt"></i></a>
                    <a class="btn btn-outline-danger btn-form" data-bs-toggle="tooltip" title="Delete" href="#"
                      data-form="#delete-statut-{{ $statut->id }}"><i class="far fa-trash-alt"></i></a>
                  </div>
                  <form id="delete-statut-{{ $statut->id }}"
                    action="{{ route('statut.destroy', ['id' => $statut->id]) }}" method="POST">
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
