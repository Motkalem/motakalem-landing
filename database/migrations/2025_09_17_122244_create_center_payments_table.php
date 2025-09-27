<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('center_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('center_patient_id')->constrained('center_patients')->onDelete('cascade');
            $table->foreignId('center_package_id')->constrained('center_packages')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('payment_type')->default('one_time'); // one_time, installment
            $table->string('status')->default('pending'); // pending, completed, failed, cancelled
            $table->boolean('is_finished')->default(false);
            $table->json('payment_data')->nullable(); // Store additional payment information
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            $table->index(['center_patient_id', 'status']);
            $table->index(['payment_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('center_payments');
    }
};
