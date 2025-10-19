<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// Import all Seeder classes manually
use Database\Seeders\UserSeeder;
use Database\Seeders\ServiceRequestSeeder;
use Database\Seeders\RequestAssignmentSeeder;
use Database\Seeders\FeedbackSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run all seeders.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ServiceRequestSeeder::class,
            RequestAssignmentSeeder::class,
            FeedbackSeeder::class,
        ]);
    }
}
