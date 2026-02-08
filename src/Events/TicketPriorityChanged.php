<?php

namespace Escalated\Laravel\Events;

use Escalated\Laravel\Enums\TicketPriority;
use Escalated\Laravel\Models\Ticket;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketPriorityChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Ticket $ticket,
        public TicketPriority $oldPriority,
        public TicketPriority $newPriority,
        public mixed $causer = null,
    ) {}
}
