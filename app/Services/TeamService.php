<?php

namespace App\Services;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class TeamService
{
    /**
     * Get all teams with relationships.
     */
    public function getAllTeams(): Collection
    {
        return Team::with(['owner', 'members', 'projects'])->get();
    }

    /**
     * Get team by ID.
     */
    public function getTeamById(int $id): ?Team
    {
        return Team::with(['owner', 'members', 'projects'])->find($id);
    }

    /**
     * Create a team with owner and optional members.
     */
    public function createTeam(array $data, User $owner): Team
    {
        return DB::transaction(function () use ($data, $owner) {
            $team = Team::create([
                'name' => $data['name'],
                'owner_id' => $owner->id,
            ]);

            if (!empty($data['members'])) {
                $team->members()->sync($data['members']);
            }

            return $team;
        });
    }

    /**
     * Update an existing team.
     */
    public function updateTeam(Team $team, array $data): bool
    {
        return DB::transaction(function () use ($team, $data) {
            $team->update(['name' => $data['name']]);

            if (array_key_exists('members', $data)) {
                $team->members()->sync($data['members']);
            }

            return true;
        });
    }

    /**
     * Delete a team.
     */
    public function deleteTeam(Team $team): bool
    {
        return DB::transaction(fn () => $team->delete());
    }
}
