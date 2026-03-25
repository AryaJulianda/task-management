@extends('layouts.app')

@section('content')
  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card app-card">
        <div class="card-body p-4">
          <h1 class="h4 mb-3">Create user</h1>
          <form method="post" action="{{ route('users.store') }}">
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
              <label class="form-label" for="role">Role</label>
              <select class="form-select" id="role" name="role" required>
                @foreach ($roles as $role)
                  <option value="{{ $role }}" @selected(old('role') === $role)>{{ $role }}</option>
                @endforeach
              </select>
            </div>
            <div class="d-flex gap-2">
              <button class="btn btn-dark" type="submit">Create user</button>
              <a class="btn btn-light" href="{{ route('users.index') }}">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
