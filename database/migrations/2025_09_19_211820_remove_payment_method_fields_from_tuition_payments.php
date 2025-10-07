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
        Schema::table('tuition_payments', function (Blueprint $table) {
            // Eliminar campos de método de pago que no son necesarios para uso personal
            $table->dropColumn(['payment_method', 'reference_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tuition_payments', function (Blueprint $table) {
            // Restaurar campos en caso de rollback
            $table->string('payment_method')->nullable();
            $table->string('reference_number')->nullable();
        });
    }
};
