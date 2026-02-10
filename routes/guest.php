<?php

use Escalated\Laravel\Http\Controllers\GuestTicketController;
use Escalated\Laravel\Http\Controllers\SatisfactionRatingController;
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
        Route::post('/{token}/rate', [SatisfactionRatingController::class, 'storeGuest'])
            ->where('token', '[A-Za-z0-9]{64}')
            ->name('escalated.guest.tickets.rate');
    });
