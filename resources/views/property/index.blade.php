@extends('layouts.app')

@section('content')
  <div class="card mx-auto">
    <div class="card-body">

      <h1 class="d-flex text-center align-items-center">
        <i class="fas fa-sitemap fa-2xs me-2"></i>Properties
        <div class="ms-auto d-flex align-items-center">
          <a class="btn btn-outline-secondary" href="{{ route('property.create') }}" title="Create" data-bs-toggle="tooltip"
            data-bs-placement="left"><i class="fas fa-plus"></i></a>
        </div>
      </h1>

      <hr />

      <table class="datatable w-100" data-page-length={{ App\Models\User::getTableLength(auth()->user()) }}>
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        @if ($properties)
          <tbody>
            @foreach ($properties as $property)
              <tr>
                <td class="text-center">{{ $property->id }}</td>
                <td>{{ $property->name }}</td>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="btn-group ms-auto">
                      <a class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Edit"
                        href="{{ route('property.edit', ['id' => $property->id]) }}"><i class="fas fa-pencil-alt"></i></a>
                      <a class="btn btn-outline-danger btn-form" data-bs-toggle="tooltip" title="Delete" href="#"
                        data-form="#delete-property-{{ $property->id }}"><i class="far fa-trash-alt"></i></a>
                    </div>
                    <form id="delete-property-{{ $property->id }}"
                      action="{{ route('property.destroy', ['id' => $property->id]) }}" method="POST">
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
