<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskActivity;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@bpkh.com'],
            [
                'name' => 'Admin BPKH',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_ADMIN,
            ]
        );

        $users = User::factory()->count(12)->create();
        $allUsers = $users->push($admin);

        $owners = $allUsers->shuffle()->take(4);
        if (!$owners->contains($admin)) {
            $owners = $owners->push($admin);
        }

        $owners->each(function (User $owner) use ($allUsers) {
            $projects = Project::factory()
                ->count(rand(2, 4))
                ->create([
                    'user_id' => $owner->id,
                ]);

            $projects->each(function (Project $project) use ($owner, $allUsers) {
                $memberPool = $allUsers
                    ->where('id', '!=', $owner->id)
                    ->values();

                $memberCount = min(5, $memberPool->count());
                $members = $memberPool->shuffle()->take(max(1, rand(1, $memberCount)));

                $memberPayload = $members
                    ->pluck('id')
                    ->mapWithKeys(fn(string $id) => [$id => ['added_at' => now()]]);

                $project->members()->syncWithoutDetaching($memberPayload->all());

                $contributors = $members->push($owner)->unique('id')->values();

                Task::factory()
                    ->count(rand(5, 12))
                    ->create([
                        'project_id' => $project->id,
                        'created_by' => $contributors->random()->id,
                    ])
                    ->each(function (Task $task) use ($contributors) {
                        $assignees = $contributors->shuffle()->take(min(3, $contributors->count()));

                        $assigneePayload = $assignees
                            ->pluck('id')
                            ->mapWithKeys(fn(string $id) => [$id => ['assigned_at' => now()]]);

                        $task->assignees()->sync($assigneePayload->all());

                        $activityUsers = $assignees->isNotEmpty() ? $assignees : $contributors;

                        TaskActivity::factory()
                            ->count(rand(1, 4))
                            ->create([
                                'task_id' => $task->id,
                                'user_id' => $activityUsers->random()->id,
                            ]);
                    });
            });
        });
    }
}
