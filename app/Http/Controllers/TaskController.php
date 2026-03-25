<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function create(Request $request, Project $project): View
    {
        $this->authorizeProject($request, $project);

        $users = $project->members()->orderBy('name')->get();
        if ($project->user_id !== $request->user()->id || $users->doesntContain('id', $project->user_id)) {
            $users->push($project->user);
        }

        $users = $users->unique('id')->sortBy('name')->values();

        return view('tasks.create', [
            'project' => $project,
            'users' => $users,
        ]);
    }

    public function store(Request $request, Project $project): RedirectResponse
    {
        $this->authorizeProject($request, $project);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:todo,in_progress,done'],
            'priority' => ['required', 'in:low,medium,high'],
            'due_date' => ['nullable', 'date'],
            'assignees' => ['nullable', 'array'],
            'assignees.*' => ['uuid', 'exists:users,id'],
        ]);

        $task = $project->tasks()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'] ?? null,
            'created_by' => $request->user()->id,
        ]);

        $this->syncAssignees($task, $validated['assignees'] ?? []);

        return redirect()->route('tasks.show', [$project, $task]);
    }

    public function show(Request $request, Project $project, Task $task): View
    {
        $this->authorizeTask($request, $project, $task);

        $task->load(['assignees', 'creator', 'activities.user']);

        return view('tasks.show', [
            'project' => $project,
            'task' => $task,
        ]);
    }

    public function edit(Request $request, Project $project, Task $task): View
    {
        $this->authorizeTask($request, $project, $task);

        $task->load('assignees');

        $users = $project->members()->orderBy('name')->get();
        if ($project->user_id !== $request->user()->id || $users->doesntContain('id', $project->user_id)) {
            $users->push($project->user);
        }

        $users = $users->unique('id')->sortBy('name')->values();

        return view('tasks.edit', [
            'project' => $project,
            'task' => $task,
            'users' => $users,
        ]);
    }

    public function update(Request $request, Project $project, Task $task): RedirectResponse
    {
        $this->authorizeTask($request, $project, $task);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:todo,in_progress,done'],
            'priority' => ['required', 'in:low,medium,high'],
            'due_date' => ['nullable', 'date'],
            'assignees' => ['nullable', 'array'],
            'assignees.*' => ['uuid', 'exists:users,id'],
        ]);

        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'] ?? null,
        ]);

        $this->syncAssignees($task, $validated['assignees'] ?? []);

        return redirect()->route('tasks.show', [$project, $task]);
    }

    public function destroy(Request $request, Project $project, Task $task): RedirectResponse
    {
        $this->authorizeTask($request, $project, $task);

        $task->delete();

        return redirect()->route('projects.show', $project);
    }

    private function authorizeProject(Request $request, Project $project): void
    {
        $userId = $request->user()->id;

        if ($project->user_id === $userId) {
            return;
        }

        abort_unless($project->members()->where('users.id', $userId)->exists(), 403);
    }

    private function authorizeTask(Request $request, Project $project, Task $task): void
    {
        $this->authorizeProject($request, $project);
        abort_unless($task->project_id === $project->id, 404);
    }

    /**
     * @param list<string> $assignees
     */
    private function syncAssignees(Task $task, array $assignees): void
    {
        $payload = collect($assignees)
            ->mapWithKeys(fn(string $userId) => [$userId => ['assigned_at' => now()]]);

        $task->assignees()->sync($payload->all());
    }
}
