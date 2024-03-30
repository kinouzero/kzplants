<div class="last-margin-0 mb-1">
  @if ($plant->tags->count() > 0)
    @foreach ($plant->tags as $tag)
      @include('layouts.tag.badge', ['tag' => $tag])
    @endforeach
  @else
    @include('layouts.alert', [
        'color' => 'secondary',
        'class' => 'text-center mb-0',
        'content' => 'No tag set',
    ])
  @endif
</div>

<div class="card">
  <a class="card-header bg-white text-decoration-none d-flex flex-nowrap align-items-center collapsed"
    href="#tags-herited-%s" role="button" data-bs-toggle="collapse" data-bs-target="#tags-herited-%s" aria-expanded="false"
    aria-controls="tags-herited-%s">
    <i class="fas fa-diagram-predecessor me-2"></i>Inherited
  </a>
  <div class="card-body collapse" id="tags-herited-%s">
    @include('layouts.strain.tags', ['strain' => $plant->strain])
  </div>
</div>
