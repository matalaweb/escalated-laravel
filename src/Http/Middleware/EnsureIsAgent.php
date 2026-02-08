<?php

namespace Escalated\Laravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAgent
{
    public function handle(Request $request, Closure $next): Response
    {
        $gate = config('escalated.authorization.agent_gate', 'escalated-agent');

        if (! Gate::check($gate)) {
            abort(403, 'You are not authorized as a support agent.');
        }

        return $next($request);
    }
}
