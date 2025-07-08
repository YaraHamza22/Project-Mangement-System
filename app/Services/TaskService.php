<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    /**
     * Get tasks for a specific project.
     */
    public function getTasksForProject(Project $project): Collection
    {
        return Task::where('project_id', $project->id)
                   ->with(['assignee', 'comments', 'attachments'])
                   ->get();
    }

    /**
     * Find a task by ID with relationships.
     */
    public function getTaskById(int $id): ?Task
    {
        return Task::with(['project', 'assignee', 'comments', 'attachments'])->find($id);
    }

    /**
     * Create a new task.
     */
    public function createTask(array $data): Task
    {
        return DB::transaction(function () use ($data) {
            return Task::create($data);
        });
    }

    /**
     * Update an existing task.
     */
    public function updateTask(Task $task, array $data): bool
    {
        return DB::transaction(function () use ($task, $data) {
            $task->update($data);
            return true;
        });
    }

    /**
     * Delete a task.
     */
    public function deleteTask(Task $task): bool
    {
        return DB::transaction(fn () => $task->delete());
    }

    /**
     * Get count of completed tasks for a project.
     */
    public function getCompletedTasksCount(int $projectId): int
    {
        return Task::where('project_id', $projectId)
                   ->where('status', 'completed') // Adjust status value if using enums
                   ->count();
    }
}
