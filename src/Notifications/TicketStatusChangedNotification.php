<?php

namespace Escalated\Laravel\Notifications;

use Escalated\Laravel\Enums\TicketStatus;
use Escalated\Laravel\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Ticket $ticket,
        public TicketStatus $oldStatus,
        public TicketStatus $newStatus,
    ) {}

    public function via(object $notifiable): array
    {
        return config('escalated.notifications.channels', ['mail', 'database']);
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("[{$this->ticket->reference}] Status Updated: {$this->newStatus->label()}")
            ->line("The status of your ticket has been updated.")
            ->line("**From:** {$this->oldStatus->label()}")
            ->line("**To:** {$this->newStatus->label()}")
            ->action('View Ticket', url(config('escalated.routes.prefix').'/'.$this->ticket->reference))
            ->line('Thank you for using our support system.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'status_changed',
            'ticket_id' => $this->ticket->id,
            'reference' => $this->ticket->reference,
            'old_status' => $this->oldStatus->value,
            'new_status' => $this->newStatus->value,
        ];
    }
}
