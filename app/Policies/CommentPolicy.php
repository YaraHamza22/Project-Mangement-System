<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
   public function view(User $user, Comment $comment): bool
    {
        return $comment->commentable->users->contains($user);
    }

    public function create(User $user): bool
    {
        return $user->can('add comments');
    }

    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id && $user->can('add comments');
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id && $user->can('add comments');
    }
}
