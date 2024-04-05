@extends('template.app')

@section('content')
  {!! $style !!}

  <div class="card mx-auto mb-3">
    <div class="card-body pb-0">

      <div class="d-flex align-items-center">
        <h1 class="d-flex text-center align-items-center mb-0">
          @if ($default = $plant->defaultPicture())
            <a class="me-2" href="{{ route('picture.src', ['id' => $default->id]) }}"
              data-lightbox="main-{{ $plant->id }}" data-title="{{ $default->name }}">
              <img class="img-thumbnail p-0 rounded object-fit-cover"
                src="{{ route('picture.src', ['id' => $default->id]) }}" alt="{{ $default->name }}"
                style="width:50px;height:50px;" />
            </a>
          @else
            <i class="fas fa-cannabis fa-2xs me-2"></i>
          @endif
          {{ $plant->name }}
        </h1>
        <span class="border py-1 rounded ms-auto"
          style="border-color: {{ $plant->statut->color }}!important;color: {{ $plant->statut->color }};padding:0 3rem;">
          {{ $plant->statut->name }}
        </span>
      </div>

      <hr />

      <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3 masonry">


        {{-- Watering --}}
        <div class="col">

          <div class="card mb-3">
            <div class="card-body">

              <h4 class="d-flex align-items-center">
                <i class="fas fa-droplet fa-xs me-2"></i>
                <span>Next watering</span>
              </h4>

              <hr />

              <div class="d-flex flex-nowrap">
                <p class="d-flex align-items-center mb-0">
                  <i
                    class="fas fa-{{ $plant->nextWateringChemical() ? 'biohazard text-danger' : 'water text-primary' }}"></i>
                  <span class="ms-2">With{{ $plant->nextWateringChemical() ? '' : 'out' }} chemical</span>
                </p>
                <a href="#"
                  class="btn btn-outline-{{ !$plant->nextWateringChemical() ? 'primary' : 'secondary' }} btn-form ms-auto"
                  data-form="#water-wo-chem" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Without chemical">
                  <i class="fas fa-water"></i>
                </a>
                <a href="#"
                  class="btn btn-outline-{{ $plant->nextWateringChemical() ? 'danger' : 'secondary' }} btn-form ms-1"
                  data-form="#water-w-chem" data-bs-toggle="tooltip" data-bs-placement="bottom" title="With chemical">
                  <i class="fas fa-biohazard"></i>
                </a>
                <form id="water-wo-chem" action="{{ route('water', ['id' => $plant->id]) }}" method="POST">
                  @csrf
                </form>
                <form id="water-w-chem" action="{{ route('water.chem', ['id' => $plant->id]) }}" method="POST">
                  @csrf
                </form>
              </div>

            </div>
          </div>

        </div>

        {{-- Checklists --}}
        <div class="col">

          <div class="card mb-3">
            <div class="card-body pb-0">

              <h4 class="d-flex align-items-center">
                <i class="fas fa-list-check fa-xs me-2"></i>
                <span>Checklists</span>
                <a class="btn btn-outline-secondary ms-auto" title="Add" data-bs-toggle="tooltip"
                  data-bs-placement="left" href="{{ route('plant.checklists', ['id' => $plant->id]) }}">
                  <i class="fas fa-plus"></i>
                </a>
              </h4>

              <hr />

              {!! $plant->templateChecklists() !!}

            </div>
          </div>

        </div>

        {{-- Comment --}}
        <div class="col">

          <div class="card mb-3">
            <div class="card-body">

              <form action="{{ route('comment.new', ['id' => $plant->id]) }}" method="POST">
                @csrf
                <h4 class="d-flex align-items-center">
                  <i class="fas fa-comment fa-xs me-2"></i>
                  <span>New comment</span>

                  <button type="submit" class="btn btn-outline-success ms-auto" data-bs-toggle="tooltip" title="Save"
                    data-bs-placement="left">
                    <i class="far fa-save"></i>
                  </button>
                </h4>

                <hr />

                @include('template.form.floating', [
                    'type' => 'textarea',
                    'id' => 'comment',
                    'name' => 'comment',
                    'label' => 'Comment',
                    'value' => '',
                    'class' => ['parent' => 'mb-3'],
                    'extra' => ['input' => 'required style="height:8rem"'],
                ])

              </form>

            </div>
          </div>

        </div>

        {{-- Details --}}
        <div class="col">

          <div class="card mb-3">
            <div class="card-body">

              <h4><i class="far fa-square-poll-horizontal me-2"></i>Details</h4>

              <hr />

              <div class="card mb-3">
                <div class="card-body">

                  <h5 class="d-flex flex-nowrap align-items-center">
                    <i class="fas fa-tags fa-xs me-2"></i>
                    <span>Tags</span>
                    <a class="btn btn-outline-secondary ms-auto" title="Add" data-bs-toggle="tooltip"
                      data-bs-placement="left" href="{{ route('plant.edit', ['id' => $plant->id]) }}">
                      <i class="fas fa-plus"></i>
                    </a>
                  </h5>

                  <hr />

                  {!! $plant->templateTags() !!}

                </div>
              </div>

              <div class="card">
                <div class="card-body">

                  <h5 class="d-flex flex-nowrap align-items-center">
                    <i class="fas fa-sitemap fa-xs me-2"></i>
                    <span>Properties</span>
                    <a class="btn btn-outline-secondary ms-auto" title="Add" data-bs-toggle="tooltip"
                      data-bs-placement="left" href="{{ route('plant.edit', ['id' => $plant->id]) }}">
                      <i class="fas fa-plus"></i>
                    </a>
                  </h5>

                  <hr />

                  {!! $plant->templateProperties() !!}

                </div>
              </div>

            </div>
          </div>

        </div>

        {{-- History --}}
        <div class="col">

          <div class="card mb-3">
            <div class="card-body">

              <h4 class="d-flex align-items-center">
                <i class="fas fa-timeline fa-90 fa-xs me-2"></i>
                <span>History</span>
              </h4>

              <hr />
              <div class="overflow-auto" style="max-height: 25rem">

                {!! $plant->templateTimeline() !!}

              </div>
            </div>
          </div>

        </div>

      </div>

    </div>
  </div>

  @include('template.picture.gallery', ['object' => $plant, 'class' => ''])

  <script>
    $('.comment-edit').click(function() {
      $(this).toggleClass('btn-outline-secondary btn-outline-danger').find('i').toggleClass('fa-pencil-alt fa-times')
      $(this).closest('.comment-actions').find('.comment-save').toggleClass('d-none');

      let commentId = $(this).closest('form').find('input[name="comment_id"]').val()
      let commentDom = $(this).closest('.card').find('.comment-value');

      if ($(this).hasClass('btn-outline-danger')) {
        let commentValue = commentDom.html();
        commentDom.html(
          '<div class="form-floating"><textarea class="form-control" required style="height:8rem" name="comment" id="comment-' +
          commentId + '">' + commentValue + '</textarea><label for="comment-' + commentId +
          '">Comment</label></div>')
      } else {
        let commentValue = commentDom.find('textarea').html();
        commentDom.html(commentValue)
      }
    });
  </script>
@endsection
