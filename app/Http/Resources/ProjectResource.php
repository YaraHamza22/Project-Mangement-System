<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'status'      => $this->status,
            'due_date'    => $this->due_date,
            'team'        => new \App\Http\Resources\TeamResource($this->whenLoaded('team')),
            'creator'     => new \App\Http\Resources\UserResource($this->whenLoaded('creator')),
            'tasks'       => \App\Http\Resources\TaskResource::collection($this->whenLoaded('tasks')),
            'members'     => \App\Http\Resources\UserResource::collection($this->whenLoaded('users')),
            'created_at'  => $this->created_at,
        ];
    }
}
