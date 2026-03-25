@extends('layouts.app')

@section('content')
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card app-card">
        <div class="card-body p-4">
          <h1 class="h4 mb-3">Edit project</h1>
          <form method="post" action="{{ route('projects.update', $project) }}">
            @csrf
            @method('put')
            <div class="mb-3">
              <label class="form-label" for="name">Project name</label>
              <input class="form-control" id="name" name="name" type="text" value="{{ old('name', $project->name) }}" required>
            </div>
            <div class="mb-3">
              <label class="form-label" for="description">Description</label>
              <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $project->description) }}</textarea>
            </div>
            <div class="d-flex gap-2">
              <button class="btn btn-dark" type="submit">Update</button>
              <a class="btn btn-light" href="{{ route('projects.show', $project) }}">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
