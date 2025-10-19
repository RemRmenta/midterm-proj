<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceRequest;
use App\Models\User;

class ServiceRequestSeeder extends Seeder
{
    public function run(): void
    {
        $residents = User::where('role', 'resident')->pluck('id')->toArray();

        foreach ($residents as $residentId) {
            ServiceRequest::factory(rand(2, 5))->create([
                'user_id' => $residentId,
            ]);
        }
    }
}
