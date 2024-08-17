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


        Schema::create('installment_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->string('hyper_payment_id')->nullable();
            $table->string('registration_id')->nullable();
            $table->float('installment_amount');
            $table->longText('card')->nullable();
            $table->longText('billing')->nullable();
            $table->longText('initiat_status')->nullable();
            $table->longText('schedule_result')->nullable();
            $table->longText('schedule_job')->nullable();
            $table->longText('cancel_result')->nullable();
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
        Schema::dropIfExists('installment_payments');
    }
};
