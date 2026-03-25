@extends('layouts.app')

@section('content')
  <div class="row justify-content-center">
    <div class="col-lg-9">
      <div class="card app-card">
        <div class="card-body p-4">
          <h1 class="h4 mb-3">Create task</h1>
          <form method="post" action="{{ route('tasks.store', $project) }}">
            @csrf
            <div class="mb-3">
              <label class="form-label" for="title">Title</label>
              <input class="form-control" id="title" name="title" type="text" value="{{ old('title') }}" required>
            </div>
            <div class="mb-3">
              <label class="form-label" for="description">Description</label>
              <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
            </div>
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label" for="status">Status</label>
                <select class="form-select" id="status" name="status" required>
                  <option value="todo" @selected(old('status') === 'todo')>Todo</option>
                  <option value="in_progress" @selected(old('status') === 'in_progress')>In progress</option>
                  <option value="done" @selected(old('status') === 'done')>Done</option>
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label" for="priority">Priority</label>
                <select class="form-select" id="priority" name="priority" required>
                  <option value="low" @selected(old('priority') === 'low')>Low</option>
                  <option value="medium" @selected(old('priority', 'medium') === 'medium')>Medium</option>
                  <option value="high" @selected(old('priority') === 'high')>High</option>
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label" for="due_date">Due date</label>
                <input class="form-control" id="due_date" name="due_date" type="date" value="{{ old('due_date') }}">
              </div>
            </div>
            @php
              $userMap = $users->keyBy('id');
              $oldAssignees = old('assignees', []);
            @endphp

            <div class="mt-4" data-assignee-manager>
              <label class="form-label" for="assignee-select">Assign to</label>
              <div class="row g-2">
                <div class="col-md-8">
                  <select class="form-select js-assignee-select" id="assignee-select">
                    <option value="">Cari user...</option>
                    @foreach ($users as $user)
                      <option value="{{ $user->id }}">
                        {{ $user->name }} ({{ $user->email }})
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-4 d-grid">
                  <button class="btn btn-outline-dark" type="button" data-assignee-add>Assign</button>
                </div>
              </div>

              <div class="mt-3" data-assignee-list>
                @foreach ($oldAssignees as $assigneeId)
                  @php
                    $assignee = $userMap->get($assigneeId);
                  @endphp
                  @if ($assignee)
                    <div class="d-flex align-items-center gap-2 mb-2" data-assignee-item data-user-id="{{ $assignee->id }}">
                      <span class="badge text-bg-light">{{ $assignee->name }} ({{ $assignee->email }})</span>
                      <button class="btn btn-outline-danger btn-sm assignee-remove-btn ms-auto" type="button" data-assignee-remove>Remove</button>
                      <input type="hidden" name="assignees[]" value="{{ $assignee->id }}">
                    </div>
                  @endif
                @endforeach

                @if (count($oldAssignees) === 0)
                  <p class="text-muted mb-0" data-assignee-empty>Belum ada user yang di-assign.</p>
                @endif
              </div>
            </div>
            <div class="d-flex gap-2 mt-4">
              <button class="btn btn-dark" type="submit">Save task</button>
              <a class="btn btn-light" href="{{ route('projects.show', $project) }}">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
