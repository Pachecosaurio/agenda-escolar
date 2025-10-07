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
            // Campos para integración con eventos del calendario
            $table->string('title')->nullable(); // Título del evento de pago
            $table->text('description')->nullable(); // Descripción del pago
            $table->enum('status', ['pending', 'paid', 'overdue', 'cancelled'])->default('pending');
            $table->string('payment_method')->nullable(); // Método de pago (efectivo, tarjeta, transferencia)
            $table->string('reference_number')->nullable(); // Número de referencia del pago
            $table->boolean('show_in_calendar')->default(true); // Mostrar en calendario
            $table->string('calendar_color', 7)->default('#FF5722'); // Color en calendario
            $table->datetime('due_date')->nullable(); // Fecha límite de pago
            $table->datetime('reminder_date')->nullable(); // Fecha de recordatorio
            
            // Índices para mejorar rendimiento
            $table->index(['user_id', 'status']);
            $table->index(['due_date']);
            $table->index(['payment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tuition_payments', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['due_date']);
            $table->dropIndex(['payment_date']);
            
            $table->dropColumn([
                'title',
                'description', 
                'status',
                'payment_method',
                'reference_number',
                'show_in_calendar',
                'calendar_color',
                'due_date',
                'reminder_date'
            ]);
        });
    }
};
