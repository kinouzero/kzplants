@extends('template.app')

@section('content')
  <div class="card">
    <div class="card-body">

      <h1 class="d-flex text-center align-items-center">
        <i class="fas fa-check fa-2xs me-2"></i>Notifications
        <div class="ms-auto d-flex align-items-center">
          <a class="btn btn-outline-secondary" href="{{ route('notification.create') }}" title="Create"
            data-bs-toggle="tooltip" data-bs-placement="left"><i class="fas fa-plus"></i></a>
        </div>
      </h1>

      <hr />

      <table class="datatable w-100" data-page-length={{ App\Models\User::getUserTableLength(auth()->user()) }}>
        <thead>
          <tr>
            <th>Name</th>
            <th>Configuration</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        @if ($notifications)
          <tbody>
            @foreach ($notifications as $notification)
              <tr>
                <td>{{ $notification->name }}</td>
                <td>{{ var_dump($notification->config()) }}</td>
                <td class="text-end">
                  <div class="btn-group">
                    <a class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Edit"
                      href="{{ route('notification.edit', ['id' => $notification->id]) }}"><i
                        class="fas fa-pencil-alt"></i></a>
                    @if ($notification->creator->id === auth()->user()->id)
                      <a class="btn btn-outline-danger btn-form" data-bs-toggle="tooltip" title="Delete" href="#"
                        data-form="#delete-notification-{{ $notification->id }}"><i class="far fa-trash-alt"></i></a>
                    @endif
                  </div>
                  <form id="delete-notification-{{ $notification->id }}"
                    action="{{ route('notification.destroy', ['id' => $notification->id]) }}" method="POST">
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
