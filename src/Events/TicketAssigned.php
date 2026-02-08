<?php

namespace Escalated\Laravel\Events;

use Escalated\Laravel\Models\Ticket;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketAssigned
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Ticket $ticket,
        public int $agentId,
        public mixed $causer = null,
    ) {}
}
