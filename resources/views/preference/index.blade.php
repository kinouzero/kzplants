@extends('layouts.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="d-flex text-center align-items-center">
        <i class="fas fa-sitemap fa-2xs me-2"></i>Preferences
        <div class="ms-auto d-flex align-items-center">
          <a class="btn btn-outline-secondary" href="{{ route('preference.create') }}" title="Create" data-bs-toggle="tooltip"
            data-bs-placement="left"><i class="fas fa-plus"></i></a>
        </div>
      </h1>

      <hr />

      <table class="datatable w-100" data-page-length={{ App\Models\User::getTableLength(auth()->user()) }}>
        <thead>
          <tr>
            <th>Name</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        @if ($preferences)
          <tbody>
            @foreach ($preferences as $preference)
              <tr>
                <td>{{ $preference->name }}</td>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="btn-group ms-auto">
                      <a class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Edit"
                        href="{{ route('preference.edit', ['id' => $preference->id]) }}"><i
                          class="fas fa-pencil-alt"></i></a>
                      <a class="btn btn-outline-danger btn-form" data-bs-toggle="tooltip" title="Delete" href="#"
                        data-form="#delete-preference-{{ $preference->id }}"><i class="far fa-trash-alt"></i></a>
                    </div>
                    <form id="delete-preference-{{ $preference->id }}"
                      action="{{ route('preference.destroy', ['id' => $preference->id]) }}" method="POST">
                      @csrf
                      @method('DELETE')
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        @endif
      </table>
    </div>
  </div>
@endsection
