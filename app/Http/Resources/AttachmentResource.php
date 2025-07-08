<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'file_name'      => $this->file_name,
            'file_size'      => $this->file_size,
            'mime_type'      => $this->mime_type,
            'disk'           => $this->disk,
            'path'           => $this->path,
            'attachable_type'=> class_basename($this->attachable_type),
            'attachable_id'  => $this->attachable_id,
            'created_at'     => $this->created_at,
        ];
    }
}
