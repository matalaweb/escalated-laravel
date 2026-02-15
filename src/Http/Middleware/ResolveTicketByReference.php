<?php

namespace Escalated\Laravel\Http\Middleware;

use Closure;
use Escalated\Laravel\Models\Ticket;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTicketByReference
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->route('ticket')) {
            $value = $request->route('ticket');

            if (! $value instanceof Ticket) {
                $ticket = Ticket::where('reference', $value)->firstOrFail();

                $request->route()->setParameter('ticket', $ticket);
            }
        }

        return $next($request);
    }
}
