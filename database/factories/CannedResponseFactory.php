<?php

namespace Escalated\Laravel\Database\Factories;

use Escalated\Laravel\Models\CannedResponse;
use Illuminate\Database\Eloquent\Factories\Factory;

class CannedResponseFactory extends Factory
{
    protected $model = CannedResponse::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'body' => $this->faker->paragraph(),
            'category' => $this->faker->randomElement(['greeting', 'closing', 'troubleshooting', 'billing']),
            'created_by' => 1,
            'is_shared' => true,
        ];
    }

    public function personal(): static
    {
        return $this->state(fn () => ['is_shared' => false]);
    }
}
