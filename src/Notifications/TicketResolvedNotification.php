<?php

namespace Escalated\Laravel\Notifications;

use Escalated\Laravel\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketResolvedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Ticket $ticket) {}

    public function via(object $notifiable): array
    {
        return config('escalated.notifications.channels', ['mail', 'database']);
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("[{$this->ticket->reference}] Ticket Resolved")
            ->line("Your support ticket has been resolved.")
            ->line("**Subject:** {$this->ticket->subject}")
            ->line("If you need further assistance, you can reopen this ticket.")
            ->action('View Ticket', url(config('escalated.routes.prefix').'/'.$this->ticket->reference))
            ->line('Thank you for using our support system.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'ticket_resolved',
            'ticket_id' => $this->ticket->id,
            'reference' => $this->ticket->reference,
            'subject' => $this->ticket->subject,
        ];
    }
}
