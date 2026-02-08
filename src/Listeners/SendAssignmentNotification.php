<?php

namespace Escalated\Laravel\Listeners;

use Escalated\Laravel\Events\TicketAssigned;
use Escalated\Laravel\Notifications\TicketAssignedNotification;
use Escalated\Laravel\Escalated;

class SendAssignmentNotification
{
    public function handle(TicketAssigned $event): void
    {
        $userModel = Escalated::userModel();
        $agent = $userModel::find($event->agentId);

        if ($agent) {
            $agent->notify(new TicketAssignedNotification($event->ticket));
        }
    }
}
