<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttachementRequest;
use App\Http\Requests\UpdateAttachmentRequest;
use App\Models\Attachment;
use App\Models\Project;
use App\Models\Task;
use App\Services\AttachmentService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttachmentController extends Controller
{
    use AuthorizesRequests;

    protected AttachmentService $attachmentService;

    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    public function store(StoreAttachementRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        $attachable = $this->resolveAttachable(
            $validated['attachable_type'],
            $validated['attachable_id']
        );

        if (!$attachable) {
            return response()->json(['message' => 'Invalid attachable entity'], 404);
        }

        $this->authorize('create', Attachment::class);

        $attachment = $this->attachmentService->uploadAttachment($validated, $attachable);

        if (!$attachment) {
            return response()->json(['message' => 'Failed to upload attachment'], 500);
        }

        return response()->json(['message' => 'Attachment uploaded', 'data' => $attachment], 201);
    }

    public function destroy(Attachment $attachment): JsonResponse
    {
        $this->authorize('delete', $attachment);

        $deleted = $this->attachmentService->deleteAttachment($attachment);

        return $deleted
            ? response()->json(['message' => 'Attachment deleted'])
            : response()->json(['message' => 'Failed to delete'], 500);
    }

    private function resolveAttachable(string $type, int $id): Project|Task|null
    {
        return match ($type) {
            'project' => Project::find($id),
            'task' => Task::find($id),
            default => null,
        };
    }
}
