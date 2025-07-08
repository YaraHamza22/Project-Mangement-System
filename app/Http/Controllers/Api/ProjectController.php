<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ProjectController extends Controller
{
    use AuthorizesRequests;

     protected ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Project::class);

        $projects = $this->projectService->getAllProjects();

        return response()->json(['data' => $projects]);
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $this->authorize('create', Project::class);

        $data = $request->validated();
        $data['created_by_user_id'] = Auth::id();

        $project = $this->projectService->createProject($data);

        return $project
            ? response()->json(['message' => 'Project created successfully.', 'data' => $project], 201)
            : response()->json(['message' => 'Failed to create project.'], 500);
    }

    public function show(Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $projectDetails = $this->projectService->getProjectById($project->id);

        return response()->json(['data' => $projectDetails]);
    }

    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        $updated = $this->projectService->updateProject($project, $request->validated());

        return $updated
            ? response()->json(['message' => 'Project updated successfully'])
            : response()->json(['message' => 'No changes made']);
    }

    public function destroy(Project $project): JsonResponse
    {
        $this->authorize('delete', $project);

        $deleted = $this->projectService->deleteProject($project);

        return $deleted
            ? response()->json(['message' => 'Project deleted successfully'])
            : response()->json(['message' => 'Failed to delete project'], 500);
    }

    public function assignMembers(Project $project, Request $request): JsonResponse
    {
        $this->authorize('assignTasks', $project);

        $userIds = $request->input('user_ids', []);
        $assigned = $this->projectService->assignMembers($project, $userIds);

        return $assigned
            ? response()->json(['message' => 'Members assigned successfully.'])
            : response()->json(['message' => 'Failed to assign members.'], 500);
    }

    public function topActiveProjects(): JsonResponse
    {
        $projects = $this->projectService->getTopActiveProjects();

        return response()->json(['data' => $projects]);
    }
}
