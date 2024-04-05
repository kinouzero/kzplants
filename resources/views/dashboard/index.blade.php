@extends('template.app')

@section('content')
  <div class="card">
    <div class="card-body">

      <h1 class="d-flex text-center align-items-center">
        <i class="fas fa-table-columns fa-2xs me-2"></i>Dashboards
        <div class="ms-auto d-flex align-items-center">
          <a class="btn btn-outline-secondary" href="{{ route('dashboard.create') }}" title="Create" data-bs-toggle="tooltip"
            data-bs-placement="left"><i class="fas fa-plus"></i></a>
        </div>
      </h1>

      <hr />

      <table class="datatable w-100" data-page-length={{ App\Models\User::getUserTableLength(auth()->user()) }}>
        <thead>
          <tr>
            <th>Name</th>
            <th>Plants</th>
            <th>Users</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        @if ($dashboards)
          <tbody>
            @foreach ($dashboards as $dashboard)
              <tr>
                <td>{{ $dashboard->name }}</td>
                <td>{{ $dashboard->plants()->count() }}</td>
                <td>{{ $dashboard->users()->count() }}</td>
                <td class="text-end">
                  <div class="btn-group">
                    <a class="btn btn-outline-{{ $default->id === $dashboard->id ? 'primary' : 'secondary' }} btn-form"
                      data-bs-toggle="tooltip" title="Default" href="#"
                      data-form="#default-dashboard-{{ $dashboard->id }}">
                      <i class="{{ $default->id === $dashboard->id ? 'fas' : 'far' }} fa-star"></i>
                    </a>
                    <a class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Edit"
                      href="{{ route('dashboard.edit', ['id' => $dashboard->id]) }}"><i class="fas fa-pencil-alt"></i></a>
                    @if ($dashboard->creator->id === auth()->user()->id)
                      <a class="btn btn-outline-danger btn-form" data-bs-toggle="tooltip" title="Delete" href="#"
                        data-form="#delete-dashboard-{{ $dashboard->id }}"><i class="far fa-trash-alt"></i></a>
                    @endif
                  </div>
                  <form id="default-dashboard-{{ $dashboard->id }}"
                    action="{{ route('dashboard.default', ['id' => $dashboard->id]) }}" method="POST">
                    @csrf
                  </form>
                  <form id="delete-dashboard-{{ $dashboard->id }}"
                    action="{{ route('dashboard.destroy', ['id' => $dashboard->id]) }}" method="POST">
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
