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
        Schema::create('hyperpay_web_hooks_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('installment_payment_id')->nullable();
            $table->string('type')->nullable();
            $table->json('action')->nullable();
            $table->json('payload')->nullable();
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
        Schema::dropIfExists('hyperpay_web_hooks_notifications');
    }
};
