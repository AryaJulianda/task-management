@extends('layouts.app')

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card app-card">
        <div class="card-body p-4">
          <h1 class="h4 mb-3">Login</h1>
          <div class="alert alert-info">
            <div class="fw-semibold mb-2">Demo accounts</div>
            <ul class="mb-0">
              <li>admin@bpkh.go.id (password: password123)</li>
              <li>ahmad.fauzi@bpkh.go.id (password: password123)</li>
              <li>sri.ramadhani@bpkh.go.id (password: password123)</li>
              <li>dita.kusuma@bpkh.go.id (password: password123)</li>
              <li>rafi.pratama@bpkh.go.id (password: password123)</li>
            </ul>
          </div>
          <form method="post" action="{{ route('login.store') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label" for="email">Email</label>
              <input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
              <label class="form-label" for="password">Password</label>
              <input class="form-control" id="password" name="password" type="password" required>
            </div>
            <div class="form-check mb-3">
              <input class="form-check-input" id="remember" name="remember" type="checkbox" value="1">
              <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <button class="btn btn-dark w-100" type="submit">Login</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
