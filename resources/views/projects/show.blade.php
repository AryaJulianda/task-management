@extends('layouts.app')

@section('content')
  <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
      <h1 class="h4 mb-1">{{ $project->name }}</h1>
      <p class="text-muted mb-0">{{ $project->description ?: 'No description yet.' }}</p>
    </div>
    <div class="d-flex gap-2">
      <a class="btn btn-dark" href="{{ route('tasks.create', $project) }}">New task</a>
      <a class="btn btn-outline-dark" href="{{ route('projects.edit', $project) }}">Edit project</a>
    </div>
  </div>

  <div class="card app-card mb-3">
    <div class="card-body">
      <form class="row g-3 align-items-end" method="get" action="{{ route('projects.show', $project) }}">
        <div class="col-md-4">
          <label class="form-label" for="status">Status</label>
          <select class="form-select" id="status" name="status">
            <option value="">All</option>
            <option value="todo" @selected(($filters['status'] ?? '') === 'todo')>Todo</option>
            <option value="in_progress" @selected(($filters['status'] ?? '') === 'in_progress')>In progress</option>
            <option value="done" @selected(($filters['status'] ?? '') === 'done')>Done</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label" for="priority">Priority</label>
          <select class="form-select" id="priority" name="priority">
            <option value="">All</option>
            <option value="low" @selected(($filters['priority'] ?? '') === 'low')>Low</option>
            <option value="medium" @selected(($filters['priority'] ?? '') === 'medium')>Medium</option>
            <option value="high" @selected(($filters['priority'] ?? '') === 'high')>High</option>
          </select>
        </div>
        <div class="col-md-4 d-flex gap-2">
          <button class="btn btn-dark" type="submit">Filter</button>
          <a class="btn btn-light" href="{{ route('projects.show', $project) }}">Reset</a>
        </div>
      </form>
    </div>
  </div>

  <div class="card app-card mb-3">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h6 mb-0">Project members</h2>
        @if ($isOwner)
          <span class="text-muted small">Owner bisa menambah/hapus member</span>
        @endif
      </div>

      @if ($isOwner)
        <form class="row g-2 align-items-end mb-3" method="post" action="{{ route('projects.members.store', $project) }}">
          @csrf
          <div class="col-md-6">
            <label class="form-label" for="member-email">Email member</label>
            <input class="form-control" id="member-email" name="email" type="email" placeholder="user@email.com" required>
          </div>
          <div class="col-md-3">
            <button class="btn btn-dark" type="submit">Add member</button>
          </div>
        </form>
      @endif

      <ul class="list-group list-group-flush">
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <div>
            <div class="fw-semibold">{{ $project->user->name }} (Owner)</div>
            <div class="text-muted small">{{ $project->user->email }}</div>
          </div>
        </li>
        @forelse ($project->members as $member)
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
              <div class="fw-semibold">{{ $member->name }}</div>
              <div class="text-muted small">{{ $member->email }}</div>
            </div>
            @if ($isOwner)
              <form method="post" action="{{ route('projects.members.destroy', [$project, $member]) }}">
                @csrf
                @method('delete')
                <button class="btn btn-outline-danger btn-sm" type="submit">Remove</button>
              </form>
            @endif
          </li>
        @empty
          <li class="list-group-item text-muted">Belum ada member tambahan.</li>
        @endforelse
      </ul>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Title</th>
          <th>Status</th>
          <th>Priority</th>
          <th>Due date</th>
          <th>Assignees</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($tasks as $task)
          <tr>
            <td class="fw-semibold">{{ $task->title }}</td>
            <td><span class="badge bg-secondary badge-status">{{ str_replace('_', ' ', $task->status) }}</span></td>
            <td><span class="badge bg-light text-dark badge-status">{{ $task->priority }}</span></td>
            <td>{{ $task->due_date?->format('d M Y') ?: '-' }}</td>
            <td>
              @if ($task->assignees->isEmpty())
                <span class="text-muted">-</span>
              @else
                {{ $task->assignees->pluck('name')->implode(', ') }}
              @endif
            </td>
            <td class="text-end">
              <div class="d-flex justify-content-end gap-2">
                <a class="btn btn-outline-dark btn-sm" href="{{ route('tasks.show', [$project, $task]) }}">View</a>
                <a class="btn btn-light btn-sm" href="{{ route('tasks.edit', [$project, $task]) }}">Edit</a>
                <form method="post" action="{{ route('tasks.destroy', [$project, $task]) }}">
                  @csrf
                  @method('delete')
                  <button class="btn btn-outline-danger btn-sm" type="submit">Delete</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-muted">Belum ada task di project ini.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection
