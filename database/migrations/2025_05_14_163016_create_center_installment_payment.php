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
        Schema::create('center_installment_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('medical_inquiries')->cascadeOnDelete();
            $table->foreignId('center_package_id')->constrained()->cascadeOnDelete();
            $table->text('registration_id')->nullable();
            $table->boolean('canceled')->default(false);
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('center_installment_payments');
    }
};
