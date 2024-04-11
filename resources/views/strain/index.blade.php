@extends('template.app')

@section('content')
  <div class="card">
    <div class="card-body">

      <h1 class="d-flex text-center align-items-center">
        <i class="fas fa-seedling fa-2xs me-2"></i>Strains
        <div class="ms-auto d-flex align-items-center">
          <a class="btn btn-outline-secondary" href="{{ route('strain.create') }}" title="Create" data-bs-toggle="tooltip"
            data-bs-placement="left"><i class="fas fa-plus"></i></a>
        </div>
      </h1>

      <hr />

      <table class="datatable w-100" data-page-length={{ App\Models\User::getUserTableLength(auth()->user()) }}>
        <thead>
          <tr>
            <th>Name</th>
            <th>Tags</th>
            <th>Properties</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        @if ($strains)
          <tbody>
            @foreach ($strains as $strain)
              <tr>
                <td>{{ $strain->name }}</td>
                <td>
                  {!! $strain->templateTags() !!}
                </td>
                <td>
                  {!! $strain->templateProperties() !!}
                </td>
                <td class="text-end">
                  <div class="btn-group">
                    <a class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Edit"
                      href="{{ route('strain.edit', ['id' => $strain->id]) }}"><i class="fas fa-pencil-alt"></i></a>
                    <a class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Pictures"
                      href="{{ route('strain.pictures', ['id' => $strain->id]) }}"><i class="far fa-images"></i></a>
                    <a class="btn btn-outline-danger btn-form" data-bs-toggle="tooltip" title="Delete" href="#"
                      data-form="#delete-strain-{{ $strain->id }}"><i class="far fa-trash-alt"></i></a>
                  </div>
                  <form id="delete-strain-{{ $strain->id }}"
                    action="{{ route('strain.destroy', ['id' => $strain->id]) }}" method="POST">
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
