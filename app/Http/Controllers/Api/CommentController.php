<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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

        if (! $commentable) {
            return response()->json([
                'message' => 'Target not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        $validated['commentable_type'] = get_class($commentable);
        $validated['commentable_id'] = $commentable->id;

        $comment = $this->commentService->create($validated);

        if (! $comment) {
            return response()->json([
                'message' => 'Failed to create comment.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'message' => 'Comment created successfully.',
            'data' => $comment
        ], Response::HTTP_CREATED);
    }

    public function update(UpdateCommentRequest $request, Comment $comment): JsonResponse
    {
        $this->authorize('update', $comment);

        $updated = $this->commentService->update($comment, $request->validated());

        return $updated
            ? response()->json(['message' => 'Comment updated successfully.'], Response::HTTP_OK)
            : response()->json(['message' => 'No changes made.'], Response::HTTP_OK);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        $this->authorize('delete', $comment);

        $deleted = $this->commentService->delete($comment);

        return $deleted
            ? response()->json(['message' => 'Comment deleted successfully.'], Response::HTTP_OK)
            : response()->json(['message' => 'Failed to delete comment.'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Resolve the polymorphic relationship.
     */
    protected function resolveCommentable(string $type, int $id): ?object
    {
        $map = [
            'project' => \App\Models\Project::class,
            'task' => \App\Models\Task::class,
            // Add other mappings here if needed
        ];

        $modelClass = $map[strtolower($type)] ?? null;

        return $modelClass ? $modelClass::find($id) : null;
    }
}
