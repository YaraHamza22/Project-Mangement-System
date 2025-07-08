<?php

namespace App\Services;

use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectService
{
    /**
     * Create a new class instance.
     */
       public function getTopActiveProjects(): Collection
    {
        return Cache::remember('top_active_projects', now()->addMinutes(10), function () {
            return Project::withCount('tasks')
                ->orderByDesc('tasks_count')
                ->take(5)
                ->get();
        });
    }

    public function getAllProjects()
    {
        return Project::withCount('tasks')
                      ->with(['team', 'creator'])
                      ->get();
    }

    public function getProjectById(int $id): ?Project
    {
        return Project::with(['team', 'users', 'tasks'])->find($id);
    }

    public function createProject(array $data): ?Project
    {
        try {
            return DB::transaction(function () use ($data) {
                $project = Project::create([
                    'name' => $data['name'],
                    'description' => $data['description'] ?? null,
                    'team_id' => $data['team_id'],
                    'created_by_user_id' => $data['created_by_user_id'],
                ]);

                if (isset($data['members']) && is_array($data['members'])) {
                    $project->users()->attach($data['members']);
                }

                Cache::forget('top_active_projects');

                return $project;
            });
        } catch (\Throwable $e) {
            Log::error('Error creating project: ' . $e->getMessage());
            return null;
        }
    }

    public function updateProject(Project $project, array $data): bool
    {
        try {
            $project->fill($data);
            if ($project->isDirty()) {
                $project->save();
                Cache::forget('top_active_projects');
                return true;
            }

            return false;
        } catch (\Throwable $e) {
            Log::error('Error updating project: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteProject(Project $project): bool
    {
        try {
            $deleted = $project->delete();
            Cache::forget('top_active_projects');
            return $deleted;
        } catch (\Throwable $e) {
            Log::error('Error deleting project: ' . $e->getMessage());
            return false;
        }
    }

    public function assignMembers(Project $project, array $userIds): bool
    {
        try {
            $project->users()->sync($userIds);
            return true;
        } catch (\Throwable $e) {
            Log::error('Error assigning members: ' . $e->getMessage());
            return false;
        }
    }
}
