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
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('is_recurring')->default(false);
            $table->enum('recurrence_type', ['daily', 'weekly', 'monthly', 'yearly'])->nullable();
            $table->integer('recurrence_interval')->default(1); // cada cuántos días/semanas/meses/años
            $table->json('recurrence_days')->nullable(); // días de la semana para eventos semanales
            $table->date('recurrence_end_date')->nullable(); // fecha fin de repetición
            $table->integer('recurrence_count')->nullable(); // número de repeticiones
            $table->unsignedBigInteger('parent_event_id')->nullable(); // evento padre para eventos generados
            
            $table->foreign('parent_event_id')->references('id')->on('events')->onDelete('cascade');
            $table->index(['is_recurring', 'parent_event_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['parent_event_id']);
            $table->dropIndex(['is_recurring', 'parent_event_id']);
            $table->dropColumn([
                'is_recurring',
                'recurrence_type',
                'recurrence_interval',
                'recurrence_days',
                'recurrence_end_date',
                'recurrence_count',
                'parent_event_id'
            ]);
        });
    }
};
