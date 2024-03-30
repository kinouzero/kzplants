@extends('layouts.app')

@section('content')
  <div class="card mx-auto" style="width: 300px">
    <div class="card-body">

      <h1 class="text-center">Login</h1>

      <hr />

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-floating mb-3">
          <input class="form-control" type="email" id="email" name="email" placeholder="email@exemple.com" required
            autofocus>
          <label class="form-label" for="email">Email</label>
        </div>

        <div class="form-floating">
          <input class="form-control" type="password" id="password" name="password" placeholder="xxxxxx" required>
          <label class="form-label" for="password">Password</label>
        </div>

        <hr />

        <div class="d-flex">
          <button class="btn btn-outline-success ms-auto" type="submit">Login</button>
        </div>
      </form>
    </div>
  </div>
@endsection
