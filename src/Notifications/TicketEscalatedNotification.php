<?php

namespace Escalated\Laravel\Notifications;

use Escalated\Laravel\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketEscalatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Ticket $ticket,
        public ?string $reason = null,
    ) {}

    public function via(object $notifiable): array
    {
        return config('escalated.notifications.channels', ['mail', 'database']);
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject("[Escalated] [{$this->ticket->reference}] {$this->ticket->subject}")
            ->line("A ticket has been escalated.")
            ->line("**Subject:** {$this->ticket->subject}")
            ->line("**Priority:** {$this->ticket->priority->label()}");

        if ($this->reason) {
            $mail->line("**Reason:** {$this->reason}");
        }

        return $mail
            ->action('View Ticket', url(config('escalated.routes.prefix').'/agent/tickets/'.$this->ticket->reference))
            ->line('Immediate attention is required.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'ticket_escalated',
            'ticket_id' => $this->ticket->id,
            'reference' => $this->ticket->reference,
            'reason' => $this->reason,
        ];
    }
}
