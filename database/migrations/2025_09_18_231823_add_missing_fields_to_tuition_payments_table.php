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
        // Esta migración está vacía porque las columnas ya fueron agregadas 
        // en la migración 2025_09_06_204426_add_calendar_fields_to_tuition_payments_table
        // Schema::table('tuition_payments', function (Blueprint $table) {
        //     // Campos ya agregados en migración anterior
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Esta migración está vacía porque las columnas ya fueron agregadas 
        // en la migración 2025_09_06_204426_add_calendar_fields_to_tuition_payments_table
        // Schema::table('tuition_payments', function (Blueprint $table) {
        //     // Rollback ya manejado en migración anterior
        // });
    }
};
