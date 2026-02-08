<?php

namespace Escalated\Laravel\Services;

use Illuminate\Support\Facades\Http;

class NotificationService
{
    public function sendWebhook(string $event, array $payload): void
    {
        $url = config('escalated.notifications.webhook_url');

        if (! $url) {
            return;
        }

        Http::timeout(10)->post($url, [
            'event' => $event,
            'payload' => $payload,
            'timestamp' => now()->toISOString(),
        ]);
    }

    public function getConfiguredChannels(): array
    {
        return config('escalated.notifications.channels', ['mail', 'database']);
    }
}
