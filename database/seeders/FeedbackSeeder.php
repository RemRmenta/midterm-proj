<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feedback;
use App\Models\ServiceRequest;

class FeedbackSeeder extends Seeder
{
    public function run(): void
    {
        $completedRequests = ServiceRequest::where('status', 'completed')->get();

        foreach ($completedRequests as $req) {
            Feedback::create([
                'service_request_id' => $req->id,
                'rating' => rand(3, 5),
                'comment' => fake()->sentence(12),
            ]);
        }
    }
}
