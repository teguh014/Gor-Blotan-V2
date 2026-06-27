<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->foreignId('court_id')
                  ->constrained('courts')
                  ->onDelete('cascade');

            // Full DATETIME for precise overlap checking (not just DATE)
            $table->dateTime('start_time');
            $table->dateTime('end_time');

            // Calculated backend-only: duration_hours * price_per_hour
            $table->decimal('total_price', 12, 2);

            $table->enum('status', ['pending', 'paid', 'cancelled', 'completed'])
                  ->default('pending');

            $table->timestamps();

            // Composite index to speed up overlap detection queries on court + time range
            $table->index(['court_id', 'start_time', 'end_time'], 'idx_court_overlap');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
