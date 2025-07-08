<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class NotificationService
{
    public function listForUser(int $userId): Collection
    {
        return Notification::where('notifiable_id', $userId)
                            ->where('notifiable_type', 'App\Models\User')
                            ->get();
    }

    public function markAsRead(Notification $notification): Notification
    {
        $notification->update(['read_at' => now()]);
        return $notification;
    }

    public function delete(Notification $notification): bool
    {
        return DB::transaction(fn() => $notification->delete());
    }
}
