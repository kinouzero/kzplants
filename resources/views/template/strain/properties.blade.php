<div class="last-margin-0">
  @if ($strain->properties->count() > 0)
    @foreach ($strain->properties as $property)
      @include('template.property.badge', ['property' => $property])
    @endforeach
  @else
    @include('template.alert', [
        'color' => 'secondary',
        'class' => 'text-center mb-0',
        'content' => 'No property set',
    ])
  @endif
</div>
