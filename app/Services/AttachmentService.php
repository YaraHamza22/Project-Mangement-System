<?php

namespace App\Services;

use App\Models\Attachment;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AttachmentService
{
    public function upload(UploadedFile $file, string $disk = 'public'): Attachment
    {
        return DB::transaction(function () use ($file, $disk) {
            $path = $file->store('attachments', $disk);
            return Attachment::create([
                'path'           => $path,
                'disk'           => $disk,
                'file_name'      => $file->getClientOriginalName(),
                'mime_type'      => $file->getClientMimeType(),
                'file_size'      => $file->getSize(),
                'attachable_id'  => request('attachable_id'),
                'attachable_type'=> request('attachable_type'),
            ]);
        });
    }

    public function delete(Attachment $attachment): bool
    {
        return DB::transaction(function () use ($attachment) {
            Storage::disk($attachment->disk)->delete($attachment->path);
            return $attachment->delete();
        });
    }
}
