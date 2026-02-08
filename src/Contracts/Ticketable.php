<?php

namespace Escalated\Laravel\Contracts;

interface Ticketable
{
    public function getTicketableNameAttribute(): string;

    public function getTicketableEmailAttribute(): string;

    public function getKey();

    public function getMorphClass();
}
