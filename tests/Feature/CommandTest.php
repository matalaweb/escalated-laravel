<?php

use Escalated\Laravel\Enums\TicketStatus;
use Escalated\Laravel\Events\SlaBreached;
use Escalated\Laravel\Models\EscalationRule;
use Escalated\Laravel\Models\Ticket;
use Escalated\Laravel\Models\TicketActivity;
use Illuminate\Support\Facades\Event;

it('check-sla command detects breaches', function () {
    Event::fake([SlaBreached::class]);

    Ticket::factory()->create([
        'status' => TicketStatus::Open,
        'first_response_at' => null,
        'first_response_due_at' => now()->subHour(),
        'sla_first_response_breached' => false,
    ]);

    $this->artisan('escalated:check-sla')
        ->assertSuccessful();

    Event::assertDispatched(SlaBreached::class);
});

it('close-resolved command auto-closes old resolved tickets', function () {
    config(['escalated.auto_close_resolved_after_days' => 7]);
    config(['escalated.transitions' => [
        'resolved' => ['closed', 'reopened'],
    ]]);

    $ticket = Ticket::factory()->create([
        'status' => TicketStatus::Resolved,
        'resolved_at' => now()->subDays(10),
    ]);

    $this->artisan('escalated:close-resolved')
        ->assertSuccessful();

    $ticket->refresh();
    expect($ticket->status)->toBe(TicketStatus::Closed);
});

it('close-resolved skips recently resolved tickets', function () {
    config(['escalated.auto_close_resolved_after_days' => 7]);

    $ticket = Ticket::factory()->create([
        'status' => TicketStatus::Resolved,
        'resolved_at' => now()->subDays(3),
    ]);

    $this->artisan('escalated:close-resolved')
        ->assertSuccessful();

    $ticket->refresh();
    expect($ticket->status)->toBe(TicketStatus::Resolved);
});

it('evaluate-escalations command runs rules', function () {
    EscalationRule::factory()->create([
        'conditions' => [
            ['field' => 'status', 'value' => 'open'],
            ['field' => 'age_hours', 'value' => 1],
        ],
        'actions' => [
            ['type' => 'change_priority', 'value' => 'high'],
        ],
    ]);

    Ticket::factory()->create([
        'status' => TicketStatus::Open,
        'created_at' => now()->subHours(2),
    ]);

    $this->artisan('escalated:evaluate-escalations')
        ->assertSuccessful();
});

it('purge-activities command cleans old entries', function () {
    $ticket = Ticket::factory()->create();

    TicketActivity::create([
        'ticket_id' => $ticket->id,
        'type' => 'status_changed',
        'properties' => ['test' => true],
        'created_at' => now()->subDays(100),
        'updated_at' => now()->subDays(100),
    ]);

    TicketActivity::create([
        'ticket_id' => $ticket->id,
        'type' => 'replied',
        'properties' => ['test' => true],
        'created_at' => now()->subDay(),
        'updated_at' => now()->subDay(),
    ]);

    $this->artisan('escalated:purge-activities', ['--days' => 90])
        ->assertSuccessful();

    expect(TicketActivity::count())->toBe(1);
});
