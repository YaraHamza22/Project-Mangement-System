<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProjectResource;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectController extends Controller
{
    use AuthorizesRequests;

    protected ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * Display a listing of projects.
     */
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Project::class);

        $projects = $this->projectService->getAllProjects();

        return response()->json([
            'data' => ProjectResource::collection($projects),
        ]);
    }

    /**
     * Store a newly created project.
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        $this->authorize('create', Project::class);

        $data = $request->validated();
        $data['created_by_user_id'] = Auth::id();

        $project = $this->projectService->createProject($data);

        return $project
            ? response()->json([
                'message' => 'Project created successfully.',
                'data' => new ProjectResource($project),
            ], 201)
            : response()->json(['message' => 'Failed to create project.'], 500);
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $projectDetails = $this->projectService->getProjectById($project->id);

        return response()->json([
            'data' => new ProjectResource($projectDetails),
        ]);
    }

    /**
     * Update the specified project.
     */
    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        $updated = $this->projectService->updateProject($project, $request->validated());

        return $updated
            ? response()->json(['message' => 'Project updated successfully.'])
            : response()->json(['message' => 'No changes made.']);
    }

    /**
     * Remove the specified project.
     */
    public function destroy(Project $project): JsonResponse
    {
        $this->authorize('delete', $project);

        $deleted = $this->projectService->deleteProject($project);

        return $deleted
            ? response()->json(['message' => 'Project deleted successfully.'])
            : response()->json(['message' => 'Failed to delete project.'], 500);
    }

    /**
     * Assign members to a project.
     */
    public function assignMembers(Project $project, Request $request): JsonResponse
    {
        $this->authorize('assignTasks', $project);

        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $assigned = $this->projectService->assignMembers($project, $validated['user_ids']);

        return $assigned
            ? response()->json(['message' => 'Members assigned successfully.'])
            : response()->json(['message' => 'Failed to assign members.'], 500);
    }

    /**
     * Show the top active projects.
     */
    public function topActiveProjects(): JsonResponse
    {
        $projects = $this->projectService->getTopActiveProjects();

        return response()->json([
            'data' => ProjectResource::collection($projects),
        ]);
    }
}
    