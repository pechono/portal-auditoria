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
        Schema::table('caso_final_entrevistados', function (Blueprint $table) {
            $table->string('archivo_path')->nullable()->after('descripcion_rol');
        });
    }

    public function down(): void
    {
        Schema::table('caso_final_entrevistados', function (Blueprint $table) {
            $table->dropColumn('archivo_path');
        });
    }
};
