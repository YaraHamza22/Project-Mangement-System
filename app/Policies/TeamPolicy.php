<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TeamPolicy
{
    /**
     * Determine whether the user can view any models.
     */
     public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage teams');
    }

    /**
     * Determine whether the user can view a specific team.
     */
    public function view(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('manage teams');
    }

    /**
     * Determine whether the user can create teams.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage teams');
    }

    /**
     * Determine whether the user can update the team.
     */
    public function update(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('manage teams');
    }

    /**
     * Determine whether the user can delete the team.
     */
    public function delete(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('manage teams');
    }
}
