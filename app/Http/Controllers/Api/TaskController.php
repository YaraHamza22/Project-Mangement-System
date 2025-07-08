<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use AuthorizesRequests;
    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $tasks = $this->taskService->getTasksForProject($project);

        return response()->json(['data' => $tasks]);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $this->authorize('create', Task::class);

        $task = $this->taskService->createTask($request->validated());

        if (!$task) {
            return response()->json(['message' => 'Failed to create task.'], 500);
        }

        return response()->json(['message' => 'Task created successfully.', 'data' => $task], 201);
    }

    public function show(Task $task): JsonResponse
    {
        $this->authorize('view', $task);

        return response()->json(['data' => $task]);
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $updated = $this->taskService->updateTask($task, $request->validated());

        return $updated
            ? response()->json(['message' => 'Task updated successfully.'])
            : response()->json(['message' => 'No changes made.'], 200);
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        $deleted = $this->taskService->deleteTask($task);

        return $deleted
            ? response()->json(['message' => 'Task deleted successfully.'])
            : response()->json(['message' => 'Failed to delete task.'], 500);
    }

    public function completedCount(Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $count = $this->taskService->getCompletedTasksCount($project->id);

        return response()->json(['completed_tasks_count' => $count]);
    }
}
