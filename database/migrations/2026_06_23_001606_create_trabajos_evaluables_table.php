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
        Schema::create('trabajos_evaluables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ciclo_lectivo_id')->constrained('ciclos_lectivos')->cascadeOnDelete();
            $table->string('nombre');           // ej: "TP1", "Parcial Teórico", "Defensa Final"
            $table->unsignedTinyInteger('orden')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trabajos_evaluables');
    }
};
