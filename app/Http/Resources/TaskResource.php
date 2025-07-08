<?php

namespace App\Http\Resources;

use App\Http\UserResources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
            'status'        => $this->status,
            'priority'      => $this->priority,
            'due_date'      => $this->due_date,
            'project'       => new \App\Http\Resources\ProjectResource($this->whenLoaded('project')),
            'assignee'      => new \App\Http\Resources\UserResource($this->whenLoaded('assignee')),
            'comments'      => \App\Http\Resources\CommentResource::collection($this->whenLoaded('comments')),
            'attachments'   => \App\Http\Resources\AttachmentResource::collection($this->whenLoaded('attachments')),
            'created_at'    => $this->created_at,
        ];
    }
}
    