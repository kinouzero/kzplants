<input class="form-control {{ $class }}" type="{{ $type }}" id="{{ $id }}"
  name="{{ $name }}" placeholder=" " {!! $extra !!}
  @if ($value) value="{{ $value }}" @endif />
