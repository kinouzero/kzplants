<div id="{{ $id }}" class="modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ $title }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ $action }}" method="POST">
        @csrf
        <div class="modal-body">
          {!! $body !!}
        </div>
        <div class="modal-footer">
          {!! $footer !!}
        </div>
      </form>
    </div>
  </div>
</div>
