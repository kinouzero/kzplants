<div class="last-margin-0">
  @if ($strain->tags->count() > 0)
    @foreach ($strain->tags as $tag)
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
