@extends('layouts.app')

@section('content')
  <section class="app-hero rounded-4 p-4 p-md-5">
    <div class="row align-items-center">
      <div class="col-lg-7">
        <h1 class="display-6 fw-semibold mb-3">Task Management</h1>
        <p class="lead text-muted">
          Kelola project, task, dan komentar tim dengan cepat. MVP siap dipakai untuk tracking status, prioritas,
          dan deadline task.
        </p>
        <div class="d-flex flex-wrap gap-2 mt-4">
          {{-- <a class="btn btn-dark" href="{{ route('register') }}">Mulai sekarang</a> --}}
          <a class="btn btn-outline-dark" href="{{ route('login') }}">Login</a>
        </div>
      </div>
      <div class="col-lg-5 mt-4 mt-lg-0">
        <div class="card app-card">
          <div class="card-body">
            <h2 class="h6">Fitur yang sudah tersedia</h2>
            <ul class="text-muted mb-0">
              <li>Project CRUD & ownership</li>
              <li>Kelola member project</li>
              <li>Task dengan status, priority, dan due date</li>
              <li>Assign task ke user</li>
              <li>Comment per task</li>
              <li>Filter status dan priority</li>
              <li>Manajemen user & role (admin/user)</li>
              <li>Profile & ubah password</li>
            </ul>
            <h2 class="h6 mt-4 text-danger">Data yang digunakan hanya ilustrasi</h2>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
