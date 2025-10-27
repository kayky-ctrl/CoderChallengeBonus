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
        Schema::create('superpowers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->json('classifications');

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
        Schema::dropIfExists('superpowers');
    }
};
