<div class="last-margin-0">
  @if ($strain->properties->count() > 0)
    @foreach ($strain->properties as $property)
      @include('layouts.property.badge', ['property' => $property])
    @endforeach
  @else
    @include('layouts.alert', [
        'color' => 'secondary',
        'class' => 'text-center mb-0',
        'content' => 'No property set',
    ])
  @endif
</div>
