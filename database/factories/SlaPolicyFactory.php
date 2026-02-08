<?php

namespace Escalated\Laravel\Database\Factories;

use Escalated\Laravel\Models\SlaPolicy;
use Illuminate\Database\Eloquent\Factories\Factory;

class SlaPolicyFactory extends Factory
{
    protected $model = SlaPolicy::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true).' SLA',
            'description' => $this->faker->sentence(),
            'is_default' => false,
            'first_response_hours' => [
                'low' => 24,
                'medium' => 8,
                'high' => 4,
                'urgent' => 2,
                'critical' => 1,
            ],
            'resolution_hours' => [
                'low' => 72,
                'medium' => 48,
                'high' => 24,
                'urgent' => 8,
                'critical' => 4,
            ],
            'business_hours_only' => false,
            'is_active' => true,
        ];
    }

    public function default(): static
    {
        return $this->state(fn () => ['is_default' => true]);
    }

    public function businessHoursOnly(): static
    {
        return $this->state(fn () => ['business_hours_only' => true]);
    }
}
