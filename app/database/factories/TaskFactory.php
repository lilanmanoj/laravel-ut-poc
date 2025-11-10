<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'is_completed' => false,
            'due_date' => $this->faker->optional()->dateTimeBetween('-1 week', '+1 week'),
            'status' => 'pending',
        ];
    }
}
