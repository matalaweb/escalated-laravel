<?php

namespace Escalated\Laravel\Console\Commands;

use Escalated\Laravel\Services\EscalationService;
use Illuminate\Console\Command;

class EvaluateEscalationsCommand extends Command
{
    protected $signature = 'escalated:evaluate-escalations';

    protected $description = 'Evaluate escalation rules against open tickets';

    public function handle(EscalationService $escalationService): int
    {
        $count = $escalationService->evaluateRules();

        $this->info("Escalation evaluation complete: {$count} tickets affected.");

        return self::SUCCESS;
    }
}
