@extends('template.app')

@section('content')
  <div class="card">
    <div class="card-body">

      <h1 class="d-flex text-center align-items-center">
        <i class="fas fa-check fa-2xs me-2"></i>Items
        <div class="ms-auto d-flex align-items-center">
          <a class="btn btn-outline-secondary" href="{{ route('item.create') }}" title="Create" data-bs-toggle="tooltip"
            data-bs-placement="left"><i class="fas fa-plus"></i></a>
        </div>
      </h1>

      <hr />

      <table class="datatable w-100" data-page-length={{ App\Models\User::getUserTableLength(auth()->user()) }}>
        <thead>
          <tr>
            <th>Name</th>
            <th>Checklist</th>
            <th>Parent</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        @if ($items)
          <tbody>
            @foreach ($items as $item)
              <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->checklist->name }}</td>
                <td>{{ $item->parent ? $item->parent->name : '' }}</td>
                <td class="text-end">
                  <div class="btn-group">
                    <a class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Edit"
                      href="{{ route('item.edit', ['id' => $item->id]) }}"><i class="fas fa-pencil-alt"></i></a>
                    <a class="btn btn-outline-danger btn-form" data-bs-toggle="tooltip" title="Delete" href="#"
                      data-form="#delete-checklist-item-{{ $item->id }}"><i class="far fa-trash-alt"></i></a>
                  </div>
                  <form id="delete-checklist-item-{{ $item->id }}"
                    action="{{ route('item.destroy', ['id' => $item->id]) }}" method="POST">
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
