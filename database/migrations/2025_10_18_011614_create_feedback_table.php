<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();

            // Link to Service Request
            $table->foreignId('service_request_id')
                ->constrained('service_requests')
                ->onDelete('cascade');

            $table->unsignedTinyInteger('rating')->comment('1 = Poor, 5 = Excellent');
            $table->text('comment')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
