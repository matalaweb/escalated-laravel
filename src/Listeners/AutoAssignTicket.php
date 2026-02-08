<?php

namespace Escalated\Laravel\Listeners;

use Escalated\Laravel\Events\TicketCreated;
use Escalated\Laravel\Services\AssignmentService;

class AutoAssignTicket
{
    public function __construct(protected AssignmentService $assignmentService) {}

    public function handle(TicketCreated $event): void
    {
        $ticket = $event->ticket;

        if ($ticket->assigned_to === null && $ticket->department_id !== null) {
            $this->assignmentService->autoAssign($ticket);
        }
    }
}
