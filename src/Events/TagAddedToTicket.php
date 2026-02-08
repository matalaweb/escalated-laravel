<?php

namespace Escalated\Laravel\Events;

use Escalated\Laravel\Models\Tag;
use Escalated\Laravel\Models\Ticket;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TagAddedToTicket
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Ticket $ticket,
        public Tag $tag,
    ) {}
}
