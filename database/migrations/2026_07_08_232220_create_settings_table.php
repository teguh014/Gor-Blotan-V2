<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default values
        DB::table('settings')->insert([
            ['key' => 'venue_name', 'value' => config('app.name'), 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'venue_logo', 'value' => null,               'created_at' => now(), 'updated_at' => now()],
            ['key' => 'venue_tagline', 'value' => 'Booking Lapangan Online', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
