<?php

namespace App\Http\Controllers\Api;

use App\Models\Team;
use App\Services\TeamService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TeamController extends Controller
{
    use AuthorizesRequests;

    protected TeamService $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
        $this->authorizeResource(Team::class, 'team');
    }

    /**
     * Display a listing of teams.
     */
    public function index(): JsonResponse
    {
        $teams = $this->teamService->getAllTeams();
        return response()->json([
            'data' => TeamResource::collection($teams),
        ]);
    }

    /**
     * Store a new team.
     */
    public function store(StoreTeamRequest $request): JsonResponse
    {
        $owner = Auth::user();
        $data = $request->validated();

        $team = $this->teamService->createTeam($data, $owner);

        return response()->json([
            'message' => 'Team created successfully.',
            'data' => new TeamResource($team),
        ], 201);
    }

    /**
     * Display the specified team.
     */
    public function show(Team $team): JsonResponse
    {
        return response()->json([
            'data' => new TeamResource($team),
        ]);
    }

    /**
     * Update the specified team.
     */
    public function update(UpdateTeamRequest $request, Team $team): JsonResponse
    {
        $updated = $this->teamService->updateTeam($team, $request->validated());

        return $updated
            ? response()->json(['message' => 'Team updated successfully.'])
            : response()->json(['message' => 'No changes made.']);
    }

    /**
     * Remove the specified team.
     */
    public function destroy(Team $team): JsonResponse
    {
        $deleted = $this->teamService->deleteTeam($team);

        return $deleted
            ? response()->json(['message' => 'Team deleted successfully.'])
            : response()->json(['message' => 'Failed to delete team.'], 500);
    }
}
