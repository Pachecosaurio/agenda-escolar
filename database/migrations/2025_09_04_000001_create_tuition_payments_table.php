<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('tuition_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 10, 2);
            $table->integer('scholarship_percent')->default(0);
            $table->date('payment_date')->nullable();
            $table->string('payment_type');
            $table->integer('penalty_percent')->default(0);
            $table->decimal('final_amount', 10, 2);
            $table->string('receipt')->nullable();
            $table->date('payment_start')->nullable();
            $table->date('payment_end')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    public function down() {
        Schema::dropIfExists('tuition_payments');
    }
};
