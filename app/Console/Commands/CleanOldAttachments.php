<?php

namespace App\Console\Commands;

use App\Models\Attachment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanOldAttachments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-old-attachments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete old and unused attachment than 15 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Searching for old unused attachments');

        $thresholdDate = now()->subDays(15);

        $attachments = Attachment::whereNull('attachable_id')
            ->where('created_at', '<', $thresholdDate)
            ->get();

        foreach ($attachments as $attachment) {
            Storage::delete($attachment->path);
            $attachment->delete();
            $this->line("Deleted: {$attachment->filename}");
        }

        $this->info("Done. Deleted {$attachments->count()} old attachments.");

        return Command::SUCCESS;
    }
}
