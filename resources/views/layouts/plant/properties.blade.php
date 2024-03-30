@if ($plant->properties->count() > 0)
  <div class="card mb-1">
    <div class="card-body last-margin-0">
      @foreach ($plant->properties as $property)
        @include('layouts.property.badge', ['property' => $property])
      @endforeach
    </div>
  </div>
@else
  @include('layouts.alert', [
      'color' => 'secondary',
      'class' => 'text-center mb-0',
      'content' => 'No property set',
  ])
@endif

<div class="card">
  <a class="card-header bg-white text-decoration-none d-flex flex-nowrap align-items-center collapsed"
    href="#properties-herited-%s" role="button" data-bs-toggle="collapse" data-bs-target="#properties-herited-%s"
    aria-expanded="false" aria-controls="properties-herited-%s">
    <i class="fas fa-diagram-predecessor me-2"></i>Inherited
  </a>
  <div class="card-body collapse" id="properties-herited-%s">
    @include('layouts.strain.properties', ['strain' => $plant->strain])
  </div>
</div>
