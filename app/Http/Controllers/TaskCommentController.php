<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
    public function store(Request $request, Project $project, Task $task): RedirectResponse
    {
        $this->authorizeTask($request, $project, $task);

        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
        ]);

        $task->activities()->create([
            'user_id' => $request->user()->id,
            'comment' => $validated['comment'],
        ]);

        return redirect()->route('tasks.show', [$project, $task]);
    }

    public function destroy(Request $request, Project $project, Task $task, TaskActivity $comment): RedirectResponse
    {
        $this->authorizeTask($request, $project, $task);
        abort_unless($comment->task_id === $task->id, 404);

        $userId = $request->user()->id;
        $isOwner = $project->user_id === $userId;

        abort_unless($isOwner || $comment->user_id === $userId, 403);

        $comment->delete();

        return redirect()->route('tasks.show', [$project, $task]);
    }

    private function authorizeTask(Request $request, Project $project, Task $task): void
    {
        $userId = $request->user()->id;

        if ($project->user_id !== $userId) {
            abort_unless($project->members()->where('users.id', $userId)->exists(), 403);
        }

        abort_unless($task->project_id === $project->id, 404);
    }
}
