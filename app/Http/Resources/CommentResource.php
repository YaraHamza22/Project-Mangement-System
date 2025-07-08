<?php

namespace App\Http\Resources;

use App\Http\UserResources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'content'         => $this->content,
            'user'            => new \App\Http\Resources\UserResource($this->whenLoaded('user')),
            'commentable_type'=> class_basename($this->commentable_type),
            'commentable_id'  => $this->commentable_id,
            'attachments'     => \App\Http\Resources\AttachmentResource::collection($this->whenLoaded('attachments')),
            'created_at'      => $this->created_at,
        ];
    }
}
