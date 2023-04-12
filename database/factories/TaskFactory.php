<?php

namespace Database\Factories;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->word(),
            'description' => fake()->sentence(),
            'assigned_to_id' => User::where('type', UserType::TYPE_USER->value)->inRandomOrder()->first()->id,
            'assigned_by_id' => User::where('type', UserType::TYPE_ADMIN->value)->inRandomOrder()->first()->id,
        ];
    }
}
