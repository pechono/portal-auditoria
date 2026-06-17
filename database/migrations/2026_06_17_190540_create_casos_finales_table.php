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
        Schema::create('casos_finales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('empresa');
            $table->text('antecedentes')->nullable();
            $table->enum('dificultad', ['facil', 'media', 'dificil'])->default('media');
            $table->unsignedTinyInteger('integrantes_min')->default(2);
            $table->unsignedTinyInteger('integrantes_max')->default(4);
            $table->text('resultado_esperado')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('casos_finales');
    }
};
