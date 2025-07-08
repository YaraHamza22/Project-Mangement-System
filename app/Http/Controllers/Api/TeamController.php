<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Models\Team;
use App\Services\TeamService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    use AuthorizesRequests;
   protected TeamService $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;

        $this->authorizeResource(Team::class, 'team');
    }

    public function index(): JsonResponse
    {
        $teams = $this->teamService->getAllTeams();
        return response()->json($teams);
    }

    public function store(StoreTeamRequest $request): JsonResponse
    {
        $team = $this->teamService->createTeam($request->validated());
        return response()->json($team, 201);
    }

    public function show(Team $team): JsonResponse
    {
        return response()->json($team);
    }

    public function update(UpdateTeamRequest $request, Team $team): JsonResponse
    {
        $success = $this->teamService->updateTeam($team, $request->validated());
        return response()->json(['updated' => $success]);
    }

    public function destroy(Team $team): JsonResponse
    {
        $success = $this->teamService->deleteTeam($team);
        return response()->json(['deleted' => $success]);
    }
}
