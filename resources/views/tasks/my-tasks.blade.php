@extends('layouts.app')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h1 class="h4 mb-1">My Tasks</h1>
      <p class="text-muted mb-0">Task yang di-assign ke kamu.</p>
    </div>
  </div>

  <div class="row g-3">
    @forelse ($tasks as $task)
      <div class="col-lg-6">
        <div class="card app-card h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <h2 class="h6 mb-1">{{ $task->title }}</h2>
                <div class="text-muted small">{{ $task->project?->name ?: 'Project tidak ditemukan' }}</div>
              </div>
              <span class="badge text-bg-light text-capitalize">{{ str_replace('_', ' ', $task->status) }}</span>
            </div>
            <p class="text-muted mt-2 mb-3">{{ $task->description ?: 'No description yet.' }}</p>
            <div class="d-flex flex-wrap gap-3 small text-muted">
              <div>
                Priority
                <span class="text-capitalize fw-semibold text-dark">{{ $task->priority }}</span>
              </div>
              <div>
                Due
                <span class="fw-semibold text-dark">{{ $task->due_date?->format('d M Y') ?: '-' }}</span>
              </div>
            </div>
            <div class="mt-3">
              @if ($task->project)
                <a class="btn btn-outline-dark btn-sm" href="{{ route('tasks.show', [$task->project, $task]) }}">Open</a>
              @else
                <span class="text-muted small">Project deleted</span>
              @endif
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-info">Belum ada task yang di-assign ke kamu.</div>
      </div>
    @endforelse
  </div>
@endsection
