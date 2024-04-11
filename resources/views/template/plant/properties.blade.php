@if ($plant->properties->count() > 0)
  <div class="card mb-1">
    <div class="card-body last-margin-0">
      @foreach ($plant->properties as $property)
        @include('template.property.badge', ['property' => $property])
      @endforeach
    </div>
  </div>
@else
  @include('template.alert', [
      'color' => 'secondary',
      'class' => 'text-center mb-0',
      'content' => 'No property set',
  ])
@endif

<div class="card">
  <a class="card-header text-decoration-none d-flex flex-nowrap align-items-center collapsed {{ auth()->user()->getTheme() === 'light' ? 'bg-white' : '' }}"
    href="#properties-herited-{{ $plant->id }}" role="button" data-bs-toggle="collapse"
    data-bs-target="#properties-herited-{{ $plant->id }}" aria-expanded="false"
    aria-controls="properties-herited-{{ $plant->id }}">
    <i class="fas fa-diagram-predecessor me-2"></i>Inherited
  </a>
  <div class="card-body collapse" id="properties-herited-{{ $plant->id }}">
    @include('template.strain.properties', ['strain' => $plant->strain])
  </div>
</div>
