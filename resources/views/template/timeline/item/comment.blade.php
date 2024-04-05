<div class="card">
  <div class="card-body">

    <div class="d-flex text-secondary">
      <i class="fas fa-user me-2"></i>
      {{ $comment->author->name }}
    </div>

    <hr class="my-2" />

    <form action="{{ route('comment.edit', ['id' => $comment->plant->id]) }}" method="POST">
      @csrf
      <input type="hidden" name="comment_id" value="{{ $comment->id }}" />

      <div class="comment-value">{{ $comment->value }}</div>

      <hr class="my-2" />

      <div class="d-flex flex-nowrap comment-actions">
        <button type="submit" class="btn btn-outline-success flex-fill comment-save d-none me-2"
          data-bs-toggle="tooltip" title="Save" data-bs-placement="bottom">
          <i class="far fa-save"></i>
        </button>
        <button type="button" class="btn btn-outline-secondary flex-fill comment-edit" data-bs-toggle="tooltip"
          title="Edit" data-bs-placement="bottom">
          <i class="fas fa-pencil-alt"></i>
        </button>
        <a href="#" class="btn btn-outline-danger flex-fill ms-2 btn-form" data-bs-toggle="tooltip" title="Delete"
          data-bs-placement="bottom" data-form="#comment-remove-{{ $comment->id }}">
          <i class="far fa-trash-alt"></i>
        </a>
      </div>

    </form>

  </div>
</div>

<form id="comment-remove-{{ $comment->id }}" action="{{ route('comment.remove', ['id' => $comment->plant->id]) }}"
  method="POST">
  @csrf
  <input type="hidden" name="comment_id" value="{{ $comment->id }}" />
</form>
