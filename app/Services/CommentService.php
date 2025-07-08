<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class CommentService
{
    public function listFor($model): Collection
    {
        return $model->comments()->with('attachments')->get();
    }

    public function create(array $data): Comment
    {
        return DB::transaction(function () use ($data) {
            return Comment::create($data);
        });
    }

    public function update(Comment $comment, array $data): Comment
    {
        return DB::transaction(function () use ($comment, $data) {
            $comment->update($data);
            return $comment;
        });
    }

    public function delete(Comment $comment): bool
    {
        return DB::transaction(fn() => $comment->delete());
    }
}
    