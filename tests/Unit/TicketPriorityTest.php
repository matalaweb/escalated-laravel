<?php

use Escalated\Laravel\Enums\TicketPriority;

it('has all expected priorities', function () {
    expect(TicketPriority::cases())->toHaveCount(5);
});

it('returns label for each priority', function () {
    expect(TicketPriority::Low->label())->toBe('Low');
    expect(TicketPriority::Medium->label())->toBe('Medium');
    expect(TicketPriority::High->label())->toBe('High');
    expect(TicketPriority::Urgent->label())->toBe('Urgent');
    expect(TicketPriority::Critical->label())->toBe('Critical');
});

it('returns color for each priority', function () {
    foreach (TicketPriority::cases() as $priority) {
        expect($priority->color())->toStartWith('#');
    }
});

it('returns numeric weight in ascending order', function () {
    expect(TicketPriority::Low->numericWeight())->toBe(1);
    expect(TicketPriority::Medium->numericWeight())->toBe(2);
    expect(TicketPriority::High->numericWeight())->toBe(3);
    expect(TicketPriority::Urgent->numericWeight())->toBe(4);
    expect(TicketPriority::Critical->numericWeight())->toBe(5);
});

it('can be created from string value', function () {
    expect(TicketPriority::from('low'))->toBe(TicketPriority::Low);
    expect(TicketPriority::from('critical'))->toBe(TicketPriority::Critical);
});
