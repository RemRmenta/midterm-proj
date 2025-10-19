<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RequestAssignment;
use App\Models\ServiceRequest;
use App\Models\User;

class RequestAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $workers = User::where('role', 'service_worker')->pluck('id')->toArray();
        $requests = ServiceRequest::all();

        foreach ($requests as $request) {
            RequestAssignment::create([
                'service_request_id' => $request->id,
                'worker_id' => fake()->randomElement($workers),
                'assigned_at' => now()->subDays(rand(1, 10)),
                'completed_at' => fake()->boolean(60) ? now() : null,
            ]);
        }
    }
}
