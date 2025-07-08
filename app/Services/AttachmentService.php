<?php

namespace App\Services;

use App\Models\Attachment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AttachmentService
{
    /**
     * Create a new class instance.
     */
    public function uploadAttachment(array $data, Model $attachable): ?Attachment
    {
        try {
            return DB::transaction(function () use ($data, $attachable) {
                $path = $data['file']->store('attachments');

                return $attachable->attachments()->create([
                    'user_id' => $data['user_id'],
                    'file_path' => $path,
                    'file_name' => $data['file']->getClientOriginalName(),
                    'mime_type' => $data['file']->getMimeType(),
                ]);
            });
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }

    public function deleteAttachment(Attachment $attachment): bool
    {
        try {
            Storage::delete($attachment->file_path);
            return $attachment->delete();
        } catch (\Throwable $e) {
            report($e);
            return false;
        }
    }
}
