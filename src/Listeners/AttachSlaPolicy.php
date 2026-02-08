<?php

namespace Escalated\Laravel\Listeners;

use Escalated\Laravel\Events\TicketCreated;
use Escalated\Laravel\Services\SlaService;

class AttachSlaPolicy
{
    public function __construct(protected SlaService $slaService) {}

    public function handle(TicketCreated $event): void
    {
        if (config('escalated.sla.enabled')) {
            $this->slaService->attachDefaultPolicy($event->ticket);
        }
    }
}
