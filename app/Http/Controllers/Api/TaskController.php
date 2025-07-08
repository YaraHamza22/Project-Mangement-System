<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use App\Models\Project;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Get all tasks for a project.
     */
    public function index(Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $tasks = $this->taskService->getTasksForProject($project);

        return response()->json([
            'data' => TaskResource::collection($tasks)
        ]);
    }

    /**
     * Store a new task.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $this->authorize('create', Task::class);

        $task = $this->taskService->createTask($request->validated());

        return $task
            ? response()->json([
                'message' => 'Task created successfully.',
                'data' => new TaskResource($task)
            ], 201)
            : response()->json(['message' => 'Failed to create task.'], 500);
    }

    /**
     * Show single task.
     */
    public function show(Task $task): JsonResponse
    {
        $this->authorize('view', $task);

        return response()->json([
            'data' => new TaskResource($task)
        ]);
    }

    /**
     * Update a task.
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $updated = $this->taskService->updateTask($task, $request->validated());

        return $updated
            ? response()->json(['message' => 'Task updated successfully.'])
            : response()->json(['message' => 'No changes made.']);
    }

    /**
     * Delete a task.
     */
    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        $deleted = $this->taskService->deleteTask($task);

        return $deleted
            ? response()->json(['message' => 'Task deleted successfully.'])
            : response()->json(['message' => 'Failed to delete task.'], 500);
    }

    /**
     * Count of completed tasks in a project.
     */
    public function completedCount(Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $count = $this->taskService->getCompletedTasksCount($project->id);

        return response()->json([
            'completed_tasks_count' => $count
        ]);
    }
}
