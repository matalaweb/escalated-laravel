<?php

namespace Escalated\Laravel\Console\Commands;

use Escalated\Laravel\Models\TicketActivity;
use Illuminate\Console\Command;

class PurgeActivitiesCommand extends Command
{
    protected $signature = 'escalated:purge-activities
        {--days= : Override the retention period in days}';

    protected $description = 'Delete old activity log entries';

    public function handle(): int
    {
        $days = $this->option('days') ?? config('escalated.activity_log.retention_days', 90);

        $count = TicketActivity::where('created_at', '<', now()->subDays((int) $days))->delete();

        $this->info("Purged {$count} activity entries older than {$days} days.");

        return self::SUCCESS;
    }
}
