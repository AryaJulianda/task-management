@extends('layouts.app')

@section('content')
  <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
      <h1 class="h4 mb-1">{{ $task->title }}</h1>
      <p class="text-muted mb-0">{{ $task->description ?: 'No description yet.' }}</p>
    </div>
    <div class="d-flex gap-2">
      <a class="btn btn-outline-dark" href="{{ route('tasks.edit', [$project, $task]) }}">Edit task</a>
      <a class="btn btn-light" href="{{ route('projects.show', $project) }}">Back</a>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card app-card h-100">
        <div class="card-body">
          <div class="text-muted">Status</div>
          <div class="fw-semibold text-capitalize">{{ str_replace('_', ' ', $task->status) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card app-card h-100">
        <div class="card-body">
          <div class="text-muted">Priority</div>
          <div class="fw-semibold text-capitalize">{{ $task->priority }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card app-card h-100">
        <div class="card-body">
          <div class="text-muted">Due date</div>
          <div class="fw-semibold">{{ $task->due_date?->format('d M Y') ?: '-' }}</div>
        </div>
      </div>
    </div>
  </div>

  <div class="card app-card mb-4">
    <div class="card-body">
      <h2 class="h6 mb-2">Assignees</h2>
      @if ($task->assignees->isEmpty())
        <p class="text-muted">Belum ada assignee.</p>
      @else
        <ul class="mb-0">
          @foreach ($task->assignees as $assignee)
            <li>{{ $assignee->name }} ({{ $assignee->email }})</li>
          @endforeach
        </ul>
      @endif
    </div>
  </div>

  <div class="card app-card">
    <div class="card-body">
      <h2 class="h6 mb-3">Comments</h2>
      <form method="post" action="{{ route('tasks.comments.store', [$project, $task]) }}">
        @csrf
        <div class="mb-3">
          <textarea class="form-control" name="comment" rows="3" placeholder="Tulis komentar...">{{ old('comment') }}</textarea>
        </div>
        <button class="btn btn-dark" type="submit">Add comment</button>
      </form>

      <hr>

      <div class="vstack gap-3">
        @forelse ($task->activities as $activity)
          <div class="border rounded p-3 bg-white">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <strong>{{ $activity->user->name }}</strong>
                <div class="text-muted small">{{ $activity->created_at->format('d M Y H:i') }}</div>
              </div>
              <form method="post" action="{{ route('tasks.comments.destroy', [$project, $task, $activity]) }}">
                @csrf
                @method('delete')
                <button class="btn btn-link text-danger btn-sm" type="submit">Delete</button>
              </form>
            </div>
            <p class="mb-0 mt-2">{{ $activity->comment }}</p>
          </div>
        @empty
          <p class="text-muted">Belum ada komentar.</p>
        @endforelse
      </div>
    </div>
  </div>
@endsection
