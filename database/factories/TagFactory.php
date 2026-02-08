<?php

namespace Escalated\Laravel\Database\Factories;

use Escalated\Laravel\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->word();

        return [
            'name' => ucfirst($name),
            'slug' => $name,
            'color' => $this->faker->hexColor(),
        ];
    }
}
