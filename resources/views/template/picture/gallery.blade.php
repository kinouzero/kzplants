<div class="card {{ $class }}">
  <div class="card-body pb-0">

    <h1><i class="far fa-images fa-xs me-2"></i>Gallery</h1>

    <hr />

    @if ($object->pictures->count() > 0)
      <div class="row row-cols-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 align-items-stretch">
        @foreach ($object->pictures as $picture)
          <div class="col mb-3">
            <div class="d-flex flex-column h-100 position-relative">
              <a class="mb-3" href="{{ route('picture.src', ['id' => $picture->id]) }}" data-lightbox="gallery"
                data-title="{{ $picture->name }}">
                <img class="img-thumbnail p-0 rounded object-fit-cover"
                  src="{{ route('picture.src', ['id' => $picture->id]) }}" alt="{{ $picture->name }}" />
              </a>
              @include('template.form.switch', [
                  'id' => sprintf('switch-picture-default-%s', $picture->id),
                  'class' => ['parent' => 'switch-form mt-auto mb-0', 'input' => 'me-2', 'label' => 'mb-0'],
                  'extra' => ['parent' => sprintf('data-form="#picture-default-%s"', $picture->id)],
                  'label' => $picture->pivot->default ? 'Default picture' : 'Set as default picture',
                  'active' => $picture->pivot->default,
              ])
              <form method="POST" id="picture-default-{{ $picture->id }}"
                action="{{ route(sprintf('%s.%s', strtolower(class_basename($object)), $picture->pivot->default ? 'remove' : 'add'), ['id' => $object->id, 'objectType' => 'default-picture', 'objectId' => $picture->id]) }}">
                @csrf
              </form>
            </div>
          </div>
        @endforeach
      </div>
    @else
      @include('template.alert', [
          'color' => 'secondary',
          'class' => 'text-center',
          'content' => 'No picture yet',
      ])
    @endif

  </div>
</div>
