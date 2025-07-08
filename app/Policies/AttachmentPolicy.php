<?php

namespace App\Policies;

use App\Models\Attachment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AttachmentPolicy
{
     public function view(User $user, Attachment $attachment): bool
    {
        return $attachment->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can('add attachments');
    }

    public function update(User $user, Attachment $attachment): bool
    {
        return $attachment->user_id === $user->id;
    }

    public function delete(User $user, Attachment $attachment): bool
    {
        return $attachment->user_id === $user->id;
    }
}
