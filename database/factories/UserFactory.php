<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make(value: 'password'),
            // Default role set to "user"
            'role' => 'resident',
            'address' => fake()->address(),
            'contact_number' => fake()->phoneNumber(),
            'profile_photo' => null,
            'remember_token' => Str::random(10),
        ];
    }

    // Admin factory state
    public function admin()
    {
        return $this->state(fn () => ['role' => 'admin']);
    }

    // Service Worker factory state
    public function worker()
    {
        return $this->state(fn () => ['role' => 'service_worker']);
    }

    // Regular User factory state
    public function user()
    {
        return $this->state(fn () => ['role' => 'resident']);
    }
}
