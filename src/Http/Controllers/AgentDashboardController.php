<?php

namespace Escalated\Laravel\Http\Controllers;

use Escalated\Laravel\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class AgentDashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $userId = $request->user()->getKey();

        return Inertia::render('Escalated/Agent/Dashboard', [
            'stats' => [
                'open' => Ticket::open()->count(),
                'my_assigned' => Ticket::assignedTo($userId)->open()->count(),
                'unassigned' => Ticket::unassigned()->open()->count(),
                'sla_breached' => Ticket::open()->breachedSla()->count(),
                'resolved_today' => Ticket::where('resolved_at', '>=', now()->startOfDay())->count(),
            ],
            'recentTickets' => Ticket::with(['requester', 'assignee', 'department'])
                ->latest()
                ->take(10)
                ->get(),
        ]);
    }
}
