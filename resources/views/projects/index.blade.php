@extends('layouts.app')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h1 class="h4 mb-1">Projects</h1>
      <p class="text-muted mb-0">Kelola semua project milik kamu.</p>
    </div>
    <a class="btn btn-dark" href="{{ route('projects.create') }}">New project</a>
  </div>

  <div class="row g-3">
    @forelse ($projects as $project)
      <div class="col-md-6 col-lg-4">
        <div class="card app-card h-100">
          <div class="card-body d-flex flex-column">
            <h2 class="h6">{{ $project->name }}</h2>
            <p class="text-muted flex-grow-1">{{ $project->description ?: 'No description yet.' }}</p>
            <div class="d-flex gap-2">
              <a class="btn btn-outline-dark btn-sm" href="{{ route('projects.show', $project) }}">Open</a>
              <a class="btn btn-light btn-sm" href="{{ route('projects.edit', $project) }}">Edit</a>
              <form method="post" action="{{ route('projects.destroy', $project) }}" data-confirm="true" data-confirm-message="Hapus project ini?">
                @csrf
                @method('delete')
                <button class="btn btn-outline-danger btn-sm" type="submit">Delete</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-info">Belum ada project. Mulai dengan membuat project pertama.</div>
      </div>
    @endforelse
  </div>
@endsection
