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
        Schema::create('notas_alumnos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ciclo_lectivo_id')->constrained('ciclos_lectivos')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('trabajo_evaluable_id')->constrained('trabajos_evaluables')->cascadeOnDelete();
            $table->foreignId('grupo_id')->nullable()->constrained('grupos')->nullOnDelete();
            $table->foreignId('caso_id')->nullable()->constrained('casos')->nullOnDelete();
            $table->decimal('nota', 4, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->unique(['user_id', 'trabajo_evaluable_id'], 'nota_unica_por_alumno_trabajo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas_alumnos');
    }
};
