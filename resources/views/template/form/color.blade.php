<div class="d-flex align-items-center">
  <div class="position-relative flex-fill me-3">
    <hex-input class="flex-fill" id="{{ $id }}" color="{{ str_replace('#', '', $color) }}"></hex-input>
    <label for="{{ $id }}">{{ $label }}</label>
  </div>
  <hex-color-picker color="{{ $color }}"></hex-color-picker>
  <input type="hidden" name="{{ $name }}" value="{{ $color }}" />
</div>
