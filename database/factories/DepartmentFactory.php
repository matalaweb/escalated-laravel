<?php

namespace Escalated\Laravel\Database\Factories;

use Escalated\Laravel\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement(['Support', 'Billing', 'Engineering', 'Sales', 'Marketing']);

        return [
            'name' => $name,
            'slug' => strtolower($name),
            'description' => $this->faker->sentence(),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
