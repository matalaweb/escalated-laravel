<?php

namespace Escalated\Laravel\Console\Commands;

use Escalated\Laravel\Enums\TicketStatus;
use Escalated\Laravel\Models\Ticket;
use Escalated\Laravel\Services\TicketService;
use Illuminate\Console\Command;

class CloseResolvedCommand extends Command
{
    protected $signature = 'escalated:close-resolved';

    protected $description = 'Auto-close tickets that have been resolved for longer than the configured period';

    public function handle(TicketService $ticketService): int
    {
        $days = config('escalated.tickets.auto_close_resolved_after_days', 7);

        $tickets = Ticket::where('status', TicketStatus::Resolved->value)
            ->where('resolved_at', '<=', now()->subDays($days))
            ->get();

        $count = 0;
        foreach ($tickets as $ticket) {
            $ticketService->close($ticket);
            $count++;
        }

        $this->info("Auto-closed {$count} resolved tickets older than {$days} days.");

        return self::SUCCESS;
    }
}
