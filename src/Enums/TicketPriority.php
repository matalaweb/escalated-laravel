<?php

namespace Escalated\Laravel\Enums;

enum TicketPriority: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case Urgent = 'urgent';
    case Critical = 'critical';

    public function label(): string
    {
        return match ($this) {
            self::Low => 'Low',
            self::Medium => 'Medium',
            self::High => 'High',
            self::Urgent => 'Urgent',
            self::Critical => 'Critical',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Low => '#6B7280',
            self::Medium => '#3B82F6',
            self::High => '#F59E0B',
            self::Urgent => '#F97316',
            self::Critical => '#EF4444',
        };
    }

    public function numericWeight(): int
    {
        return match ($this) {
            self::Low => 1,
            self::Medium => 2,
            self::High => 3,
            self::Urgent => 4,
            self::Critical => 5,
        };
    }
}
