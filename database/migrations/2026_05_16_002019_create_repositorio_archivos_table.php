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
        Schema::create('repositorio_archivos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('nombre_original');
            $table->string('path');
            $table->enum('categoria', ['documento', 'entrevista', 'otro'])->default('documento');
            $table->foreignId('caso_id')->nullable()->constrained('casos')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repositorio_archivos');
    }
};
