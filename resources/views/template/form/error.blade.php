@if (is_array($errors->get($key)))
  @foreach ($errors->get($key) as $msg)
    <p class="small text-danger m-1">{{ $msg }}</p>
  @endforeach
@elseif(is_string($errors->get($key)))
  <div class="small text-danger m-1">{{ $msg }}</div>
@endif
