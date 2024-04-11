@extends('template.app')

@section('content')
  <div class="card">
    <div class="card-body">

      <h1 class="d-flex align-items-center">
        <i class="fas fa-list-check fa-2xs me-2"></i>Checklists
        <div class="ms-auto d-flex align-items-center">
          <a class="btn btn-outline-secondary" href="{{ route('checklist.create') }}" title="Create" data-bs-toggle="tooltip"
            data-bs-placement="left"><i class="fas fa-plus"></i></a>
        </div>
      </h1>

      <hr />

      <table class="datatable w-100" data-page-length={{ App\Models\User::getUserTableLength(auth()->user()) }}>
        <thead>
          <tr>
            <th data-priority="1">Name</th>
            <th data-priority="2" data-orderable="false">Items</th>
            <th class="text-end" data-orderable="false">Actions</th>
          </tr>
        </thead>
        @if ($checklists)
          <tbody>
            @foreach ($checklists as $checklist)
              <tr>
                <td>{{ $checklist->name }}</td>
                <td>
                  @foreach ($checklist->items as $item)
                    <p class="mb-1">{{ $item->name }}</p>
                  @endforeach
                </td>
                <td class="text-end">
                  <div class="btn-group">
                    <a class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Edit"
                      href="{{ route('checklist.edit', ['id' => $checklist->id]) }}"><i class="fas fa-pencil-alt"></i></a>
                    <a class="btn btn-outline-danger btn-form" data-bs-toggle="tooltip" title="Delete" href="#"
                      data-form="#delete-list-{{ $checklist->id }}"><i class="far fa-trash-alt"></i></a>
                  </div>
                  <form id="delete-list-{{ $checklist->id }}"
                    action="{{ route('checklist.destroy', ['id' => $checklist->id]) }}" method="POST">
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
