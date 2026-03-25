<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $userId = $user->id;

        if ($user->isAdmin()) {
            return view('projects.index', [
                'projects' => Project::query()->latest()->get(),
            ]);
        }

        $projects = Project::query()
            ->where('user_id', $userId)
            ->orWhereHas('members', fn($query) => $query->where('users.id', $userId))
            ->latest()
            ->distinct()
            ->get();

        return view('projects.index', [
            'projects' => $projects,
        ]);
    }

    public function create(): View
    {
        return view('projects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $project = $request->user()->projects()->create($validated);

        return redirect()->route('projects.show', $project);
    }

    public function show(Request $request, Project $project): View
    {
        $this->authorizeProject($request, $project);

        $project->load('members');
        $isOwner = $project->user_id === $request->user()->id;

        $filters = $request->validate([
            'status' => ['nullable', 'in:todo,in_progress,done'],
            'priority' => ['nullable', 'in:low,medium,high'],
        ]);

        $tasksQuery = $project->tasks()->with(['assignees', 'creator'])->latest();

        if (!empty($filters['status'])) {
            $tasksQuery->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $tasksQuery->where('priority', $filters['priority']);
        }

        $memberOptions = User::query()
            ->whereNotIn('id', $project->members->pluck('id')->push($project->user_id))
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('projects.show', [
            'project' => $project,
            'tasks' => $tasksQuery->get(),
            'filters' => $filters,
            'isOwner' => $isOwner,
            'memberOptions' => $memberOptions,
        ]);
    }

    public function edit(Request $request, Project $project): View
    {
        $this->authorizeProject($request, $project);

        return view('projects.edit', [
            'project' => $project,
        ]);
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $this->authorizeProject($request, $project);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project);
    }

    public function destroy(Request $request, Project $project): RedirectResponse
    {
        $this->authorizeProject($request, $project);

        $project->delete();

        return redirect()->route('projects.index');
    }

    public function addMember(Request $request, Project $project): RedirectResponse
    {
        $this->authorizeOwner($request, $project);

        $validated = $request->validate([
            'member_id' => ['required', 'uuid', 'exists:users,id'],
        ]);

        $member = User::findOrFail($validated['member_id']);

        if ($member->id === $project->user_id) {
            return redirect()
                ->route('projects.show', $project)
                ->with('status', 'Owner sudah otomatis menjadi member.');
        }

        $project->members()->syncWithoutDetaching([
            $member->id => ['added_at' => now()],
        ]);

        return redirect()->route('projects.show', $project)->with('status', 'Member ditambahkan.');
    }

    public function removeMember(Request $request, Project $project, User $member): RedirectResponse
    {
        $this->authorizeOwner($request, $project);

        if ($member->id === $project->user_id) {
            return redirect()
                ->route('projects.show', $project)
                ->with('status', 'Owner tidak bisa dihapus dari project.');
        }

        $project->members()->detach($member->id);

        return redirect()->route('projects.show', $project)->with('status', 'Member dihapus.');
    }

    private function authorizeProject(Request $request, Project $project): void
    {
        $user = $request->user();
        $userId = $user->id;

        if ($user->isAdmin()) {
            return;
        }

        if ($project->user_id === $userId) {
            return;
        }

        abort_unless($project->members()->where('users.id', $userId)->exists(), 403);
    }

    private function authorizeOwner(Request $request, Project $project): void
    {
        if ($request->user()->isAdmin()) {
            return;
        }

        abort_unless($project->user_id === $request->user()->id, 403);
    }
}
