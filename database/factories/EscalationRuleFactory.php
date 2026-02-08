<?php

namespace Escalated\Laravel\Database\Factories;

use Escalated\Laravel\Models\EscalationRule;
use Illuminate\Database\Eloquent\Factories\Factory;

class EscalationRuleFactory extends Factory
{
    protected $model = EscalationRule::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'trigger_type' => 'time_based',
            'conditions' => [
                ['field' => 'status', 'operator' => 'equals', 'value' => 'open'],
                ['field' => 'hours_since_created', 'operator' => 'greater_than', 'value' => 24],
            ],
            'actions' => [
                ['type' => 'change_priority', 'value' => 'high'],
                ['type' => 'notify', 'value' => 'admin'],
            ],
            'order' => $this->faker->numberBetween(1, 100),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
