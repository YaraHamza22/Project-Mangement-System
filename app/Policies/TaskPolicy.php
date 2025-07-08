<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Task $task): bool
    {
        return $task->project->users->contains($user);
    }

    public function create(User $user): bool
    {
        return $user->can('assign tasks');
    }

    public function update(User $user, Task $task): bool
    {
        return $user->can('assign tasks') && (
            $task->created_by_user_id === $user->id ||
            $task->project->users->contains($user)
        );
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->can('delete task') && (
            $user->hasRole('admin') ||
            $task->created_by_user_id === $user->id
        );
    }

    public function complete(User $user, Task $task): bool
    {
        return $task->assigned_to === $user->id;
    }
}
