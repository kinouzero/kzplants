<div class="last-margin-0">
  @if ($strain->tags->count() > 0)
    @foreach ($strain->tags as $tag)
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
