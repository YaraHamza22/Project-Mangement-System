<?php

namespace App\Services;

use App\Events\TaskAssigned;
use App\Mail\TaskAssignedMail;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TaskService
{
   public function getTasksForProject(Project $project)
    {
        return $project->tasks()->with('assignedUser')->get();
    }

    public function createTask(array $data): ?Task
    {
        try {
            return DB::transaction(function () use ($data) {
                $task = Task::create($data);
                if (!empty($task->assigned_to)) {
                event(new TaskAssigned($task));}

                $this->invalidateCompletedTasksCache($task->project_id);

                return $task;
            });
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }


    public function updateTask(Task $task, array $data): bool
    {
        try {
            $task->fill($data);
            $changed = $task->isDirty('status');

            if ($task->isDirty()) {
                $task->save();
                if ($changed && isset($data['status']) && $data['status'] === 'completed') {
                    $this->invalidateCompletedTasksCache($task->project_id);
                }
                return true;
            }
            return false;
        } catch (\Throwable $e) {
            report($e);
            return false;
        }
    }
    public function deleteTask(Task $task): bool
    {
        try {
            $deleted = $task->delete();

            if ($deleted) {
                $this->invalidateCompletedTasksCache($task->project_id);
            }

            return $deleted;
        } catch (\Throwable $e) {
            report($e);
            return false;
        }
    }
    public function getCompletedTasksCount(int $projectId): int
    {
        return Cache::remember("project_{$projectId}_completed_tasks_count", now()->addMinutes(10), function () use ($projectId) {
            return Task::where('project_id', $projectId)
                ->where('status', 'completed')
                ->count();
        });
    }

    protected function invalidateCompletedTasksCache(int $projectId): void
    {
        Cache::forget("project_{$projectId}_completed_tasks_count");
    }
}
