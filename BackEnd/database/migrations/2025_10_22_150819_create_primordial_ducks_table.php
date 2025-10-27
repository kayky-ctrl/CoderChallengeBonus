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
        Schema::create('primordial_ducks', function (Blueprint $table) {
            $table->id();
            $table->string('designation');
            $table->decimal('height_cm', 8, 2);
            $table->decimal('weight_g', 10, 2);
            $table->string('last_known_city')->nullable();
            $table->string('last_known_country')->nullable();
            $table->decimal('last_known_lat', 10, 8);
            $table->decimal('last_known_lon', 11, 8);
            $table->decimal('gps_precision_m', 8, 2);
            $table->string('reference_point')->nullable();
            $table->enum('hibernation_status', ['desperto', 'em_transe', 'hibernacao_profunda']);
            $table->integer('heart_rate_bpm')->nullable();
            $table->integer('mutation_count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('primordial_ducks');
    }
};
