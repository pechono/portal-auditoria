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
        Schema::create('caso_final_documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caso_final_id')->constrained('casos_finales')->cascadeOnDelete();
            $table->string('titulo');
            $table->string('descripcion')->nullable();
            $table->string('archivo_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caso_final_documentos');
    }
};
