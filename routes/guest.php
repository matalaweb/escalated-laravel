<?php

use Escalated\Laravel\Http\Controllers\GuestTicketController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])
    ->prefix(config('escalated.routes.prefix', 'support').'/guest')
    ->group(function () {
        Route::get('/create', [GuestTicketController::class, 'create'])->name('escalated.guest.tickets.create');
        Route::post('/', [GuestTicketController::class, 'store'])->name('escalated.guest.tickets.store');
        Route::get('/{token}', [GuestTicketController::class, 'show'])
            ->where('token', '[A-Za-z0-9]{64}')
            ->name('escalated.guest.tickets.show');
        Route::post('/{token}/reply', [GuestTicketController::class, 'reply'])
            ->where('token', '[A-Za-z0-9]{64}')
            ->name('escalated.guest.tickets.reply');
    });
