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
        Schema::create('capture_missions', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['planejamento', 'em_progresso', 'sucesso', 'falha']);
            $table->text('briefing_notes')->nullable();
            $table->text('debriefing_notes')->nullable();

            $table->foreignId('capture_drone_id')
                ->constrained('capture_drones')
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
        Schema::dropIfExists('capture_missions');
    }
};
