<?php

namespace App\Services;
use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TeamService
{
    /**
     * Create a new class instance.
     */
       public function getAllTeams()
    {
        return Team::all();
    }

    public function getTopActiveTeams()
    {
        return Cache::remember('top_active_teams', now()->addMinutes(10), function () {
            return Team::withCount('projects')
                ->orderByDesc('projects_count')
                ->take(5)
                ->get();
        });
    }

    public function createTeam(array $data): ?Team
    {
        try {
            return DB::transaction(function () use ($data) {
                $team = Team::create($data);
                Cache::forget('top_active_teams');
                return $team;
            });
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }

    public function updateTeam(Team $team, array $data): bool
    {
        try {
            $team->fill($data);
            if ($team->isDirty()) {
                $team->save();
                Cache::forget('top_active_teams');
                return true;
            }
            return false;
        } catch (\Throwable $e) {
            report($e);
            return false;
        }
    }

    public function deleteTeam(Team $team): bool
    {
        try {
            $deleted = $team->delete();
            Cache::forget('top_active_teams');
            return $deleted;
        } catch (\Throwable $e) {
            report($e);
            return false;
        }
    }
}

