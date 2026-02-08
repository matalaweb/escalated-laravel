<?php

use Escalated\Laravel\Http\Controllers\AgentDashboardController;
use Escalated\Laravel\Http\Controllers\AgentTicketController;
use Escalated\Laravel\Http\Middleware\EnsureIsAgent;
use Escalated\Laravel\Http\Middleware\ResolveTicketByReference;
use Illuminate\Support\Facades\Route;

Route::middleware(array_merge(config('escalated.routes.admin_middleware', ['web', 'auth']), [EnsureIsAgent::class]))
    ->prefix(config('escalated.routes.prefix', 'support').'/agent')
    ->group(function () {
        Route::get('/', AgentDashboardController::class)->name('escalated.agent.dashboard');
        Route::get('/tickets', [AgentTicketController::class, 'index'])->name('escalated.agent.tickets.index');

        Route::middleware(ResolveTicketByReference::class)->group(function () {
            Route::get('/tickets/{ticket}', [AgentTicketController::class, 'show'])->name('escalated.agent.tickets.show');
            Route::put('/tickets/{ticket}', [AgentTicketController::class, 'update'])->name('escalated.agent.tickets.update');
            Route::post('/tickets/{ticket}/reply', [AgentTicketController::class, 'reply'])->name('escalated.agent.tickets.reply');
            Route::post('/tickets/{ticket}/note', [AgentTicketController::class, 'note'])->name('escalated.agent.tickets.note');
            Route::post('/tickets/{ticket}/assign', [AgentTicketController::class, 'assign'])->name('escalated.agent.tickets.assign');
            Route::post('/tickets/{ticket}/status', [AgentTicketController::class, 'status'])->name('escalated.agent.tickets.status');
            Route::post('/tickets/{ticket}/priority', [AgentTicketController::class, 'priority'])->name('escalated.agent.tickets.priority');
            Route::post('/tickets/{ticket}/tags', [AgentTicketController::class, 'tags'])->name('escalated.agent.tickets.tags');
            Route::post('/tickets/{ticket}/department', [AgentTicketController::class, 'department'])->name('escalated.agent.tickets.department');
        });
    });
