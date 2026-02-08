<?php

namespace Escalated\Laravel\Listeners;

use Escalated\Laravel\Events\SlaBreached;
use Escalated\Laravel\Notifications\SlaBreachNotification;

class SendSlaBreachNotification
{
    public function handle(SlaBreached $event): void
    {
        $ticket = $event->ticket;

        if ($ticket->assigned_to && $assignee = $ticket->assignee) {
            $assignee->notify(new SlaBreachNotification($ticket, $event->type));
        }
    }
}
