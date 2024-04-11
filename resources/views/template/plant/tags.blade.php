<div class="last-margin-0 mb-1">
  @if ($plant->tags->count() > 0)
    @foreach ($plant->tags as $tag)
      @include('template.tag.badge', ['tag' => $tag])
    @endforeach
  @else
    @include('template.alert', [
        'color' => 'secondary',
        'class' => 'text-center mb-0',
        'content' => 'No tag set',
    ])
  @endif
</div>

<div class="card">
  <a class="card-header text-decoration-none d-flex flex-nowrap align-items-center collapsed {{ auth()->user()->getTheme() === 'light' ? 'bg-white' : '' }}"
    href="#tags-herited-{{ $plant->id }}" role="button" data-bs-toggle="collapse"
    data-bs-target="#tags-herited-{{ $plant->id }}" aria-expanded="false"
    aria-controls="tags-herited-{{ $plant->id }}">
    <i class="fas fa-diagram-predecessor me-2"></i>Inherited
  </a>
  <div class="card-body collapse" id="tags-herited-{{ $plant->id }}">
    @include('template.strain.tags', ['strain' => $plant->strain])
  </div>
</div>
