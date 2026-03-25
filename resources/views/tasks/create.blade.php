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
            <div class="mt-4">
              <label class="form-label">Assign to</label>
              <div class="row">
                @forelse ($users as $user)
                  <div class="col-md-4">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="assignees[]" id="assignee-{{ $user->id }}" value="{{ $user->id }}" @checked(in_array($user->id, old('assignees', [])))>
                      <label class="form-check-label" for="assignee-{{ $user->id }}">{{ $user->name }}</label>
                    </div>
                  </div>
                @empty
                  <p class="text-muted">Belum ada user lain untuk di-assign.</p>
                @endforelse
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
