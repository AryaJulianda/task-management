@extends('layouts.app')

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card app-card">
        <div class="card-body p-4">
          <h1 class="h4 mb-3">Register</h1>
          <form method="post" action="{{ route('register.store') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label" for="name">Name</label>
              <input class="form-control" id="name" name="name" type="text" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
              <label class="form-label" for="email">Email</label>
              <input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
              <label class="form-label" for="password">Password</label>
              <input class="form-control" id="password" name="password" type="password" required>
            </div>
            <div class="mb-3">
              <label class="form-label" for="password_confirmation">Confirm password</label>
              <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" required>
            </div>
            <button class="btn btn-dark w-100" type="submit">Create account</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
