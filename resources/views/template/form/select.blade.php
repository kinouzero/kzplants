<select class="form-control {{ $class }}" id="{{ $id }}" name="{{ $name }}"
  data-placeholder="{{ $placeholder ?: __('ui.select') }}" {!! $extra !!}>
  <option value="">{{ $placeholder ?: __('ui.select') }}</option>
  {!! $options !!}
</select>
