<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Services\CommentService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use AuthorizesRequests;
      protected CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(StoreCommentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $commentable = $this->resolveCommentable(
            $validated['commentable_type'],
            $validated['commentable_id']
        );

        if (!$commentable) {
            return response()->json(['message' => 'Target not found'], 404);
        }

        $comment = $this->commentService->createComment($validated, $commentable);

        if (!$comment) {
            return response()->json(['message' => 'Failed to create comment'], 500);
        }

        return response()->json(['message' => 'Comment created', 'data' => $comment], 201);
    }

    public function update(UpdateCommentRequest $request, Comment $comment): JsonResponse
    {
        $this->authorize('update', $comment);

        $updated = $this->commentService->updateComment($comment, $request->validated());

        return $updated
            ? response()->json(['message' => 'Comment updated successfully'])
            : response()->json(['message' => 'No changes made'], 200);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        $this->authorize('delete', $comment);

        $deleted = $this->commentService->deleteComment($comment);

        return $deleted
            ? response()->json(['message' => 'Comment deleted successfully.'])
            : response()->json(['message' => 'Failed to delete comment.'], 500);
    }

    private function resolveCommentable(string $type, int $id): Task|Project|null
    {
        return match ($type) {
            'task' => Task::find($id),
            'project' => Project::find($id),
            default => null,
        };
    }

}
