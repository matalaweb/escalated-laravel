<?php

namespace Escalated\Laravel\Listeners;

use Escalated\Laravel\Events\TicketCreated;
use Escalated\Laravel\Notifications\NewTicketNotification;

class SendNewTicketNotifications
{
    public function handle(TicketCreated $event): void
    {
        $ticket = $event->ticket;

        // Notify the requester
        if ($requester = $ticket->requester) {
            $requester->notify(new NewTicketNotification($ticket));
        }

        // Notify assigned agent if any
        if ($ticket->assigned_to && $assignee = $ticket->assignee) {
            $assignee->notify(new NewTicketNotification($ticket));
        }
    }
}
