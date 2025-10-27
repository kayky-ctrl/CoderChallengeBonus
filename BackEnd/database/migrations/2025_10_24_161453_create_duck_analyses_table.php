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
        Schema::create('duck_analyses', function (Blueprint $table) {
            $table->id();
            $table->decimal('operational_cost', 12, 2);
            $table->enum('military_power_needed', ['nenhum', 'baixo', 'medio', 'alto', 'extremo']);
            $table->enum('risk_level', ['minimo', 'baixo', 'medio', 'alto', 'critico']);
            $table->integer('scientific_value');
            $table->integer('capture_priority');
            $table->text('analysis_notes')->nullable();

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
        Schema::dropIfExists('duck_analyses');
    }
};
