<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('request_assignments', function (Blueprint $table) {
            $table->id();

            // Service request being assigned
            $table->foreignId('service_request_id')
                ->constrained('service_requests')
                ->onDelete('cascade');

            // Service worker assigned
            $table->foreignId('worker_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_assignments');
    }
};
