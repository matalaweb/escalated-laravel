<?php

namespace Escalated\Laravel\Policies;

use Escalated\Laravel\Models\Ticket;
use Illuminate\Support\Facades\Gate;

class TicketPolicy
{
    public function viewAny($user): bool
    {
        return true;
    }

    public function view($user, Ticket $ticket): bool
    {
        if (Gate::allows('escalated-agent', $user) || Gate::allows('escalated-admin', $user)) {
            return true;
        }

        return $ticket->requester_id === $user->getKey()
            && $ticket->requester_type === $user->getMorphClass();
    }

    public function create($user): bool
    {
        return true;
    }

    public function update($user, Ticket $ticket): bool
    {
        return Gate::allows('escalated-agent', $user) || Gate::allows('escalated-admin', $user);
    }

    public function reply($user, Ticket $ticket): bool
    {
        if (Gate::allows('escalated-agent', $user) || Gate::allows('escalated-admin', $user)) {
            return true;
        }

        return $ticket->requester_id === $user->getKey()
            && $ticket->requester_type === $user->getMorphClass();
    }

    public function addNote($user, Ticket $ticket): bool
    {
        return Gate::allows('escalated-agent', $user) || Gate::allows('escalated-admin', $user);
    }

    public function assign($user, Ticket $ticket): bool
    {
        return Gate::allows('escalated-agent', $user) || Gate::allows('escalated-admin', $user);
    }

    public function close($user, Ticket $ticket): bool
    {
        if (Gate::allows('escalated-agent', $user) || Gate::allows('escalated-admin', $user)) {
            return true;
        }

        $isRequester = $ticket->requester_id === $user->getKey()
            && $ticket->requester_type === $user->getMorphClass();

        return $isRequester && config('escalated.allow_customer_close', false);
    }
}
