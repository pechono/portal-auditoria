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
        Schema::table('casos', function (Blueprint $table) {
            $table->tinyInteger('dificultad')->default(1)->after('activo');
            $table->enum('tipo', ['grupal', 'individual'])->default('grupal')->after('dificultad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('casos', function (Blueprint $table) {
            $table->dropColumn(['dificultad', 'tipo']);
        });
    }
};
