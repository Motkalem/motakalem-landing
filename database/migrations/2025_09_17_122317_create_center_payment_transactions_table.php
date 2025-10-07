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
        Schema::create('center_payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('center_payment_id')->constrained('center_payments')->onDelete('cascade');
            $table->foreignId('center_patient_id')->constrained('center_patients')->onDelete('cascade');
            $table->string('transaction_id')->unique(); // HyperPay transaction ID
            $table->string('title');
            $table->decimal('amount', 10, 2);
            $table->string('success')->default('false'); // true, false
            $table->string('payment_method')->nullable(); // VISA, MASTERCARD, MADA, etc.
            $table->json('data'); // Store complete HyperPay response
            $table->timestamps();
            
            $table->index(['center_payment_id', 'success']);
            $table->index(['center_patient_id', 'success']);
            $table->index('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('center_payment_transactions');
    }
};
