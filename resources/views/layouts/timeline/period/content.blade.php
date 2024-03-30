<h2 class="timeline-title text-lg mb-2">{!! $content !!}</h2>
@if ($date)
  <p class="text-secondary small mb-0">
    <i class="far fa-calendar-alt me-2"></i>{{ $date->format('d/m/Y') }}
    <i class="far fa-clock mx-2"></i>{{ $date->format('H:i') }}
  </p>
@elseif($next)
  <p class="text-secondary small mb-0">
    <i class="fas fa-droplet"></i>
    <i class="fas fa-arrow-right mx-2"></i>
    <i class="fas fa-{{ $next }}"></i>
  </p>
@endif
