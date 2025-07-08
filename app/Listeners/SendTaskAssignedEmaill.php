<?php

namespace App\Listeners;

use App\Events\TaskAssigned;
use App\Mail\TaskAssignedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendTaskAssignedEmaill
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
   public function handle(TaskAssigned $event): void
    {
        $task = $event->task;

        if ($task->assignedUser && $task->assigned->email) {
            Mail::to($task->assignee->email)
                ->queue(new TaskAssignedMail($task));
        }
    }

}
