<?php

namespace Escalated\Laravel\Listeners;

use Escalated\Laravel\Events\ReplyCreated;
use Escalated\Laravel\Notifications\TicketReplyNotification;

class SendReplyNotifications
{
    public function handle(ReplyCreated $event): void
    {
        $reply = $event->reply;
        $ticket = $reply->ticket;

        // If agent replied, notify the customer
        if ($ticket->requester && $reply->author_id !== $ticket->requester_id) {
            $ticket->requester->notify(new TicketReplyNotification($reply));
        }

        // If customer replied, notify the assigned agent
        if ($ticket->assigned_to && $ticket->assignee && $reply->author_id !== $ticket->assigned_to) {
            $ticket->assignee->notify(new TicketReplyNotification($reply));
        }
    }
}
