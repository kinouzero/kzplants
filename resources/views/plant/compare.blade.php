@extends('template.app')

@section('content')
  <div class="card">
    <div class="card-body">
      <h1><i class="fas fa-images fa-xs me-2"></i>Compare photos</h1>

      <hr />

      @php
        $pictures = $plant->pictures;
        $first = $pictures->first();
        $last = $pictures->last();
      @endphp

      @if (!$first || !$last || $first->id === $last->id)
        @include('template.alert', ['color' => 'secondary', 'class' => 'mb-0', 'content' => 'Need at least 2 photos to compare'])
      @else
        <div class="compare-wrapper mb-3">
          <img src="{{ route('picture.src', ['id' => $first->id]) }}" alt="{{ $first->name }}" />
          <div class="compare-overlay" data-compare-overlay>
            <img src="{{ route('picture.src', ['id' => $last->id]) }}" alt="{{ $last->name }}" />
          </div>
        </div>
        <input class="compare-slider" type="range" min="0" max="100" value="50" data-compare-slider />
      @endif
    </div>
  </div>
@endsection
