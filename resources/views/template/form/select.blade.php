<select class="form-control {{ $class }}" id="{{ $id }}" name="{{ $name }}"
  data-placeholder="{{ $placeholder ?: 'Select' }}" {!! $extra !!}>
  <option value="">{{ $placeholder ?: 'Select' }}</option>
  {!! $options !!}
</select>
