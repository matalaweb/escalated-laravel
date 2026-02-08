<?php

namespace Escalated\Laravel\Services;

use Carbon\Carbon;
use Escalated\Laravel\Events\SlaBreached;
use Escalated\Laravel\Events\SlaWarning;
use Escalated\Laravel\Models\SlaPolicy;
use Escalated\Laravel\Models\Ticket;

class SlaService
{
    public function attachDefaultPolicy(Ticket $ticket): void
    {
        $policy = SlaPolicy::active()->default()->first();

        if (! $policy) {
            return;
        }

        $this->attachPolicy($ticket, $policy);
    }

    public function attachPolicy(Ticket $ticket, SlaPolicy $policy): void
    {
        $ticket->sla_policy_id = $policy->id;

        $firstResponseHours = $policy->getFirstResponseHoursFor($ticket->priority);
        $resolutionHours = $policy->getResolutionHoursFor($ticket->priority);

        if ($firstResponseHours) {
            $ticket->first_response_due_at = $this->calculateDueDate(
                $ticket->created_at, $firstResponseHours, $policy->business_hours_only
            );
        }

        if ($resolutionHours) {
            $ticket->resolution_due_at = $this->calculateDueDate(
                $ticket->created_at, $resolutionHours, $policy->business_hours_only
            );
        }

        $ticket->save();
    }

    public function checkBreaches(): int
    {
        $breached = 0;
        $now = now();

        $tickets = Ticket::open()
            ->whereNotNull('first_response_due_at')
            ->whereNull('first_response_at')
            ->where('sla_first_response_breached', false)
            ->where('first_response_due_at', '<', $now)
            ->get();

        foreach ($tickets as $ticket) {
            $ticket->update(['sla_first_response_breached' => true]);
            SlaBreached::dispatch($ticket, 'first_response');
            $breached++;
        }

        $tickets = Ticket::open()
            ->whereNotNull('resolution_due_at')
            ->where('sla_resolution_breached', false)
            ->where('resolution_due_at', '<', $now)
            ->get();

        foreach ($tickets as $ticket) {
            $ticket->update(['sla_resolution_breached' => true]);
            SlaBreached::dispatch($ticket, 'resolution');
            $breached++;
        }

        return $breached;
    }

    public function checkWarnings(int $warningMinutes = 30): int
    {
        $warned = 0;
        $now = now();
        $threshold = $now->copy()->addMinutes($warningMinutes);

        $tickets = Ticket::open()
            ->whereNotNull('first_response_due_at')
            ->whereNull('first_response_at')
            ->where('sla_first_response_breached', false)
            ->whereBetween('first_response_due_at', [$now, $threshold])
            ->get();

        foreach ($tickets as $ticket) {
            $minutes = (int) $now->diffInMinutes($ticket->first_response_due_at);
            SlaWarning::dispatch($ticket, 'first_response', $minutes);
            $warned++;
        }

        $tickets = Ticket::open()
            ->whereNotNull('resolution_due_at')
            ->where('sla_resolution_breached', false)
            ->whereBetween('resolution_due_at', [$now, $threshold])
            ->get();

        foreach ($tickets as $ticket) {
            $minutes = (int) $now->diffInMinutes($ticket->resolution_due_at);
            SlaWarning::dispatch($ticket, 'resolution', $minutes);
            $warned++;
        }

        return $warned;
    }

    protected function calculateDueDate(Carbon $from, float $hours, bool $businessHoursOnly): Carbon
    {
        if (! $businessHoursOnly) {
            return $from->copy()->addHours($hours);
        }

        $config = config('escalated.sla.business_hours');
        $start = $config['start'] ?? '09:00';
        $end = $config['end'] ?? '17:00';
        $timezone = $config['timezone'] ?? 'UTC';
        $days = $config['days'] ?? [1, 2, 3, 4, 5];

        $current = $from->copy()->timezone($timezone);
        $remainingMinutes = $hours * 60;

        while ($remainingMinutes > 0) {
            if (in_array($current->dayOfWeek, $days)) {
                $dayStart = $current->copy()->setTimeFromTimeString($start);
                $dayEnd = $current->copy()->setTimeFromTimeString($end);

                if ($current->lt($dayStart)) {
                    $current = $dayStart;
                }

                if ($current->lt($dayEnd)) {
                    $availableMinutes = $current->diffInMinutes($dayEnd);
                    if ($availableMinutes >= $remainingMinutes) {
                        return $current->addMinutes($remainingMinutes);
                    }
                    $remainingMinutes -= $availableMinutes;
                }
            }

            $current = $current->addDay()->setTimeFromTimeString($start);
        }

        return $current;
    }
}
