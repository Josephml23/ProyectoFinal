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
        Schema::table('users', function (Blueprint $table) {
            // Añadir las nuevas columnas
            if (!Schema::hasColumn('users', 'lastname')) {
                $table->string('lastname')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'dni')) {
                // Necesitamos el índice único si no existe
                $table->string('dni', 20)->nullable()->unique()->after('lastname');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->string('address')->nullable()->after('dni');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            
            // 💡 SOLUCIÓN: Solo eliminar las columnas si sabemos que existen
            
            // Eliminar 'address'
            if (Schema::hasColumn('users', 'address')) {
                $table->dropColumn('address');
            }
            
            // Eliminar índice único 'dni' y luego la columna 'dni'
            if (Schema::hasColumn('users', 'dni')) {
                 // Primero elimina el índice único (necesario si la columna se creó)
                 $table->dropUnique(['dni']);
                 $table->dropColumn('dni');
            }
            
            // Eliminar 'lastname'
            if (Schema::hasColumn('users', 'lastname')) {
                $table->dropColumn('lastname');
            }
        });
    }
};