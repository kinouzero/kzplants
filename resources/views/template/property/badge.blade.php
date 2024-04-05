@switch (true)
  @case (Str::contains(strtolower($property->name), 'url'))
    <p class="mb-1"><a href="{{ $property->pivot->value }}" class="badge bg-primary text-light text-decoration-none"
        target="_blank"><i class="fas fa-link me-2"></i>{{ $property->pivot->value }}</a></p>
  @break

  @case (Str::contains(strtolower($property->name), '%'))
    <p class="mb-1">
      <span
        class="badge bg-{{ ($property->pivot->value >= 0 && $property->pivot->value < 15) ||
        strtolower($property->pivot->value) === 'low'
            ? 'danger'
            : (($property->pivot->value >= 15 && $property->pivot->value < 25) ||
            strtolower($property->pivot->value) === 'medium'
                ? 'warning'
                : ($property->pivot->value >= 25 || strtolower($property->pivot->value) === 'high'
                    ? 'success'
                    : '')) }}">
        {{ preg_replace('/\s*%\s*/', '', $property->name) }} :
        {{ $property->pivot->value }}{{ is_numeric($property->pivot->value) ? '%' : '' }}
      </span>
    </p>
  @break

  @default
    <p class="mb-1"><span class="badge bg-secondary">{{ $property->name }} : {{ $property->pivot->value }}</span></p>
@endswitch
