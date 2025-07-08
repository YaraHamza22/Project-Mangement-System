<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CommentService
{
    /**
     * Create a new class instance.
     */
    public function createComment(array $data, Model $commentable): ?Comment
    {
        try {
            return DB::transaction(function () use ($data, $commentable) {
                return $commentable->comments()->create($data);
            });
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }

    public function updateComment(Comment $comment, array $data): bool
    {
        try {
            $comment->fill($data);

            if ($comment->isDirty()) {
                $comment->save();
                return true;
            }

            return false;
        } catch (\Throwable $e) {
            report($e);
            return false;
        }
    }

    public function deleteComment(Comment $comment): bool
    {
        try {
            return $comment->delete();
        } catch (\Throwable $e) {
            report($e);
            return false;
        }
    }
}
