<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ServiceRequest;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RequestAssignment>
 */
class RequestAssignmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'service_request_id' => ServiceRequest::factory(),
            'worker_id' => User::factory()->worker(),
            'assigned_at' => now(),
            'completed_at' => fake()->boolean(70) ? now() : null,
        ];
    }
}
