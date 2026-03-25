@extends('layouts.app')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h1 class="h4 mb-1">Users</h1>
      <p class="text-muted mb-0">Kelola user dan role akses.</p>
    </div>
    <a class="btn btn-dark" href="{{ route('users.create') }}">Create user</a>
  </div>

  @if (session('generated_password'))
    <div class="alert alert-warning">
      Password baru untuk user tersebut: <strong>{{ session('generated_password') }}</strong>
    </div>
  @endif

  <div class="card app-card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table mb-0">
          <thead class="table-light">
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Created at</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($users as $user)
              @php
                $isSelf = auth()->id() === $user->id;
              @endphp
              <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                  <form class="d-flex gap-2 user-role-form" method="post" action="{{ route('users.role', $user) }}" data-initial-role="{{ $user->role }}">
                    @csrf
                    @method('put')
                    <select class="form-select form-select-sm" name="role" aria-label="Role" @disabled($isSelf)>
                      <option value="{{ \App\Models\User::ROLE_ADMIN }}" @selected($user->role === \App\Models\User::ROLE_ADMIN)>
                        {{ \App\Models\User::ROLE_ADMIN }}
                      </option>
                      <option value="{{ \App\Models\User::ROLE_USER }}" @selected($user->role === \App\Models\User::ROLE_USER)>
                        {{ \App\Models\User::ROLE_USER }}
                      </option>
                    </select>
                    <button class="btn btn-outline-dark btn-sm" type="submit" @disabled($isSelf)>Save</button>
                  </form>
                </td>
                <td>{{ $user->created_at?->format('d M Y, H:i') }}</td>
                <td>
                  <button class="btn btn-outline-danger btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#deleteUserModal" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}" @disabled($isSelf)>
                    Delete
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td class="text-muted" colspan="5">Belum ada user.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form class="modal-content" method="post" action="{{ route('users.destroy') }}">
        @csrf
        @method('delete')
        <div class="modal-header">
          <h5 class="modal-title" id="deleteUserModalLabel">Konfirmasi hapus user</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="mb-2">Kamu akan menghapus user: <strong data-user-name></strong></p>
          <label class="form-label" for="admin_password">Password admin</label>
          <input class="form-control" id="admin_password" name="admin_password" type="password" required>
          <input name="user_id" type="hidden" required>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Hapus</button>
        </div>
      </form>
    </div>
  </div>
@endsection
