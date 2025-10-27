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
        Schema::create('capture_drones', function (Blueprint $table) {
            $table->id();
            $table->string('designation')->unique();
            $table->enum('status', ['ocioso', 'em_missao', 'carregando', 'manutencao']);
            $table->decimal('battery_percent', 5, 2);
            $table->decimal('fuel_percent', 5, 2);
            $table->decimal('integrity_percent', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capture_drones');
    }
};
