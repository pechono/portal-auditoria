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
        Schema::create('entregas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('grupos')->cascadeOnDelete();
            $table->foreignId('etapa_id')->constrained('etapas');
            $table->string('archivo_path');
            $table->string('archivo_nombre');
            $table->enum('estado', ['enviada', 'aprobada', 'con_observaciones', 'rechazada'])->default('enviada');
            $table->text('comentario_docente')->nullable();
            $table->string('devolucion_path')->nullable();
            $table->foreignId('revisado_por')->nullable()->constrained('users');
            $table->timestamp('revisado_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entregas');
    }
};
