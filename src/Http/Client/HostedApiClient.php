<?php

namespace Escalated\Laravel\Http\Client;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class HostedApiClient
{
    protected string $baseUrl;

    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('escalated.hosted.api_url', 'https://cloud.escalated.dev/api/v1'), '/');
        $this->apiKey = config('escalated.hosted.api_key', '');
    }

    public function emit(string $event, array $payload): ?Response
    {
        return $this->post('/events', [
            'event' => $event,
            'payload' => $payload,
            'timestamp' => now()->toISOString(),
        ]);
    }

    public function sendCommand(string $command, array $payload): ?Response
    {
        return $this->post('/commands', [
            'command' => $command,
            'payload' => $payload,
        ]);
    }

    public function query(string $endpoint, array $params = []): ?Response
    {
        return $this->get($endpoint, $params);
    }

    protected function post(string $endpoint, array $data): ?Response
    {
        return Http::withHeaders($this->headers())
            ->timeout(15)
            ->post($this->baseUrl.$endpoint, $data);
    }

    protected function get(string $endpoint, array $params = []): ?Response
    {
        return Http::withHeaders($this->headers())
            ->timeout(15)
            ->get($this->baseUrl.$endpoint, $params);
    }

    protected function headers(): array
    {
        return [
            'Authorization' => 'Bearer '.$this->apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }
}
