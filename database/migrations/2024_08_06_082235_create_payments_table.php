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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('package_id');
            $table->string('payment_type');
            $table->string('payment_url');
            $table->boolean('is_finished')->default(false);
            $table->timestamps();

            // Optional: Adding foreign keys if you have students and packages tables
            // $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            // $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
