@extends('template.app')

@section('content')
  <div class="card">
    <div class="card-body">

      <h1 class="d-flex text-center align-items-center">
        <i class="fas fa-tags fa-2xs me-2"></i>Tags
        <div class="ms-auto d-flex align-items-center">
          <a class="btn btn-outline-secondary" href="{{ route('tag.create') }}" title="Create" data-bs-toggle="tooltip"
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
        @if ($tags)
          <tbody>
            @foreach ($tags as $tag)
              <tr>
                <td>{{ $tag->name }}</td>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="rounded me-2" style="height:30px;width:30px;background-color:{{ $tag->color }}"></div>
                    <span>{{ $tag->color }}</span>
                  </div>
                </td>
                <td class="text-end">
                  <div class="btn-group">
                    <a class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Edit"
                      href="{{ route('tag.edit', ['id' => $tag->id]) }}"><i class="fas fa-pencil-alt"></i></a>
                    <a class="btn btn-outline-danger btn-form" data-bs-toggle="tooltip" title="Delete" href="#"
                      data-form="#delete-tag-{{ $tag->id }}"><i class="far fa-trash-alt"></i></a>
                  </div>
                  <form id="delete-tag-{{ $tag->id }}" action="{{ route('tag.destroy', ['id' => $tag->id]) }}"
                    method="POST">
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
