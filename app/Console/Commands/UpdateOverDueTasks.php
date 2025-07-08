<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;

class UpdateOverDueTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-over-due-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'mark task as overdue if due date has passed and status is still pendibg';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for overdue tasks');

        $count = Task::where('status', 'pending')
            ->where('due_date', '<', now())
            ->update(['status' => 'overdue']);

        $this->info("Updated $count overdue tasks.");

        return Command::SUCCESS;
    }
}
