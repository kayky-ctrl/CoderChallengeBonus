<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sighting_logs', function (Blueprint $table) {
            $table->id();
            $table->json('raw_data_payload');
            $table->timestamp('sighted_at');

            $table->foreignId('survey_drone_id')
                ->constrained('survey_drones')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('primordial_duck_id')
                ->constrained('primordial_ducks')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sighting_logs');
    }
};
