<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceRequest>
 */
class ServiceRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->user(),
            'type' => fake()->randomElement(['Water Leak', 'Low Pressure', 'Meter Replacement', 'New Connection']),
            'description' => fake()->sentence(10),
            'address' => fake()->address(),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'status' => fake()->randomElement(['pending', 'in_progress', 'completed']),
            'proof_of_service' => null,
        ];
    }
}
