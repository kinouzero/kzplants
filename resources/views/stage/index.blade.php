@extends('template.app')

@section('content')
  <div class="card">
    <div class="card-body">

      <h1 class="d-flex align-items-center">
        <i class="fas fa-layer-group fa-2xs me-2"></i>{{ __('app.stages') }}
        <div class="ms-auto d-flex align-items-center">
          <a class="btn btn-outline-secondary" href="{{ route('stage.create') }}" title="{{ __('ui.create') }}" data-bs-toggle="tooltip"
            data-bs-placement="left"><i class="fas fa-plus"></i></a>
        </div>
      </h1>

      <hr />

      <table class="datatable w-100" data-page-length={{ App\Models\User::getUserTableLength(auth()->user()) }}>
        <thead>
          <tr>
            <th data-priority="1">{{ __('ui.name') }}</th>
            <th data-priority="2">{{ __('ui.checklist') }}</th>
            <th data-priority="3">{{ __('ui.order') }}</th>
            <th data-priority="4">{{ __('ui.interval_stage_days') }}</th>
            <th data-priority="5">{{ __('ui.interval_item_days') }}</th>
            <th class="text-end" data-orderable="false">{{ __('ui.actions') }}</th>
          </tr>
        </thead>
        @if ($stages)
          <tbody>
            @foreach ($stages as $stage)
              <tr>
                <td>{{ $stage->name }}</td>
                <td>{{ $stage->checklist ? $stage->checklist->name : '-' }}</td>
                <td>{{ $stage->order }}</td>
                <td>{{ $stage->interval_stage_days }}</td>
                <td>{{ $stage->interval_item_days }}</td>
                <td class="text-end">
                  <div class="btn-group">
                    <a class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="{{ __('ui.edit') }}"
                      href="{{ route('stage.edit', ['id' => $stage->id]) }}"><i class="fas fa-pencil-alt"></i></a>
                    <a class="btn btn-outline-danger btn-form" data-bs-toggle="tooltip" title="{{ __('ui.delete') }}" href="#"
                      data-form="#delete-stage-{{ $stage->id }}"><i class="far fa-trash-alt"></i></a>
                  </div>
                  <form id="delete-stage-{{ $stage->id }}"
                    action="{{ route('stage.destroy', ['id' => $stage->id]) }}" method="POST">
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
