<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            
            // Resident who created the request
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->string('type'); // e.g. 'Water Leak', 'New Connection', etc.
            $table->text('description');
            $table->string('address')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->string('proof_of_service')->nullable(); // uploaded image or file path

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
