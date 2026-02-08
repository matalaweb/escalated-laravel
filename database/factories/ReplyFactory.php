<?php

namespace Escalated\Laravel\Database\Factories;

use Escalated\Laravel\Models\Reply;
use Escalated\Laravel\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReplyFactory extends Factory
{
    protected $model = Reply::class;

    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::factory(),
            'author_type' => 'App\\Models\\User',
            'author_id' => 1,
            'body' => $this->faker->paragraphs(2, true),
            'is_internal_note' => false,
            'type' => 'reply',
        ];
    }

    public function internalNote(): static
    {
        return $this->state(fn () => [
            'is_internal_note' => true,
            'type' => 'note',
        ]);
    }

    public function systemMessage(): static
    {
        return $this->state(fn () => [
            'type' => 'system',
            'body' => 'Status changed to resolved.',
        ]);
    }
}
