<?php

namespace App\Services;

use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class ProjectService
{
    /**
     * Get all projects with relationships.
     */
    public function getAllProjects(): Collection
    {
        return Project::with(['team', 'creator', 'tasks', 'users'])->get();
    }

    /**
     * Get a specific project by ID with relationships.
     */
    public function getProjectById(int $id): ?Project
    {
        return Project::with(['team', 'creator', 'tasks', 'users'])->find($id);
    }

    /**
     * Create a new project.
     */
    public function createProject(array $data): ?Project
    {
        return DB::transaction(function () use ($data) {
            $project = Project::create([
                'name'               => $data['name'],
                'description'        => $data['description'] ?? null,
                'status'             => $data['status'],
                'due_date'           => $data['due_date'] ?? null,
                'team_id'            => $data['team_id'],
                'created_by_user_id' => $data['created_by_user_id'],
            ]);

            if (!empty($data['members'])) {
                $project->users()->sync($data['members']);
            }

            return $project;
        });
    }

    /**
     * Update an existing project.
     */
    public function updateProject(Project $project, array $data): bool
    {
        return DB::transaction(function () use ($project, $data) {
            $project->update($data);

            if (array_key_exists('members', $data)) {
                $project->users()->sync($data['members']);
            }

            return true;
        });
    }

    /**
     * Delete a project.
     */
    public function deleteProject(Project $project): bool
    {
        return DB::transaction(fn () => $project->delete());
    }

    /**
     * Assign members to a project.
     */
    public function assignMembers(Project $project, array $userIds): bool
    {
        return DB::transaction(function () use ($project, $userIds) {
            $project->users()->sync($userIds);
            return true;
        });
    }

    /**
     * Get the top 5 projects with the most tasks.
     */
    public function getTopActiveProjects(): Collection
    {
        return Project::withCount('tasks')
            ->orderByDesc('tasks_count')
            ->take(5)
            ->get();
    }
}
