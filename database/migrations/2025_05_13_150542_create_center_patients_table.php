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
        Schema::create('center_patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('mobile_number')->nullable();
            $table->string('age')->nullable();
            $table->boolean('is_paid')->default(0);
            $table->string('email')->nullable();
            $table->string('id_end_date')->nullable();
            $table->string('id_number')->nullable();
            $table->string('source')->nullable();
            $table->string('city')->nullable();
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
        Schema::table('center_patients', function (Blueprint $table) {

            $table->dropIfExists();
        });
    }
};
