<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
     public function viewAny(User $user): bool
    {
        return $user->can('view project');
    }

    public function view(User $user, Project $project): bool
    {
        return $user->can('view project') && $project->users->contains($user);
    }

    public function create(User $user): bool
    {
        return $user->can('manage projects');
    }

    public function update(User $user, Project $project): bool
    {
        return $user->can('manage projects') && $project->created_by_user_id === $user->id;
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->can('manage projects') && $project->created_by_user_id === $user->id;
    }

    public function assignTasks(User $user, Project $project): bool
    {
        return $user->can('assign tasks') && $project->users->contains($user);
    }
}
