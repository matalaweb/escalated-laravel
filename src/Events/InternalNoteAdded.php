<?php

namespace Escalated\Laravel\Events;

use Escalated\Laravel\Models\Reply;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InternalNoteAdded
{
    use Dispatchable, SerializesModels;

    public function __construct(public Reply $note) {}
}
