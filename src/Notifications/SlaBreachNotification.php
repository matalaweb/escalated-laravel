<?php

namespace Escalated\Laravel\Notifications;

use Escalated\Laravel\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SlaBreachNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Ticket $ticket,
        public string $breachType,
    ) {}

    public function via(object $notifiable): array
    {
        return config('escalated.notifications.channels', ['mail', 'database']);
    }

    public function toMail(object $notifiable): MailMessage
    {
        $typeLabel = $this->breachType === 'first_response' ? 'First Response' : 'Resolution';

        return (new MailMessage)
            ->subject("[SLA Breach] [{$this->ticket->reference}] {$typeLabel} SLA Breached")
            ->line("An SLA has been breached on ticket {$this->ticket->reference}.")
            ->line("**Type:** {$typeLabel} SLA")
            ->line("**Subject:** {$this->ticket->subject}")
            ->line("**Priority:** {$this->ticket->priority->label()}")
            ->action('View Ticket', url(config('escalated.routes.prefix').'/agent/tickets/'.$this->ticket->reference))
            ->line('Immediate attention is required.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'sla_breach',
            'ticket_id' => $this->ticket->id,
            'reference' => $this->ticket->reference,
            'breach_type' => $this->breachType,
        ];
    }
}
