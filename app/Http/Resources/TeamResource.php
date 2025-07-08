<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'owner'     => new \App\Http\Resources\UserResource($this->whenLoaded('owner')),
            'members'   => \App\Http\Resources\UserResource::collection($this->whenLoaded('members')),
            'projects'  => \App\Http\Resources\ProjectResource::collection($this->whenLoaded('projects')),
            'created_at'=> $this->created_at,
        ];
    }
}
