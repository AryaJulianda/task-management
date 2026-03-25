@extends('layouts.app')

@section('content')
  <div class="row g-4">
    <div class="col-lg-6">
      <div class="card app-card">
        <div class="card-body p-4">
          <h2 class="h5 mb-3">Update profile</h2>
          <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('put')
            <div class="mb-3">
              <label class="form-label" for="name">Name</label>
              <input class="form-control" id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="mb-3">
              <label class="form-label" for="email">Email</label>
              <input class="form-control" id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
            </div>
            <button class="btn btn-dark" type="submit">Save changes</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card app-card">
        <div class="card-body p-4">
          <h2 class="h5 mb-3">Change password</h2>
          <form method="post" action="{{ route('profile.password') }}">
            @csrf
            @method('put')
            <div class="mb-3">
              <label class="form-label" for="current_password">Current password</label>
              <input class="form-control" id="current_password" name="current_password" type="password" required>
            </div>
            <div class="mb-3">
              <label class="form-label" for="password">New password</label>
              <input class="form-control" id="password" name="password" type="password" required>
            </div>
            <div class="mb-3">
              <label class="form-label" for="password_confirmation">Confirm new password</label>
              <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" required>
            </div>
            <button class="btn btn-dark" type="submit">Update password</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
