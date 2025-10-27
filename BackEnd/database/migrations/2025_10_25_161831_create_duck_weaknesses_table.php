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
        Schema::create('duck_weaknesses', function (Blueprint $table) {
            $table->id();
            $table->string('discovered_by')->nullable();

            $table->foreignId('primordial_duck_id')
                ->constrained('primordial_ducks')
                ->onUpdate('cascade');
                // ->onDelete('cascade');

            $table->foreignId('weakness_id')
                ->constrained('weaknesses')
                ->onUpdate('cascade');
                // ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duck_weaknesses');
    }
};
