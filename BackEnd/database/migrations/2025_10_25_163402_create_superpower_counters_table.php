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
        Schema::create('superpower_counters', function (Blueprint $table) {
            $table->id();
            $table->string('effectiveness_notes')->nullable();

            $table->foreignId('superpower_id')
                ->constrained('superpowers')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('defense_system_id')
                ->constrained('defense_systems')
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
        Schema::dropIfExists('superpower_counters');
    }
};
