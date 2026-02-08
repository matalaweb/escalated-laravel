<?php

namespace Escalated\Laravel\Listeners;

use Escalated\Laravel\Events\TicketStatusChanged;
use Escalated\Laravel\Notifications\TicketStatusChangedNotification;

class SendStatusChangeNotification
{
    public function handle(TicketStatusChanged $event): void
    {
        $ticket = $event->ticket;

        if ($requester = $ticket->requester) {
            $requester->notify(new TicketStatusChangedNotification(
                $ticket, $event->oldStatus, $event->newStatus
            ));
        }
    }
}
