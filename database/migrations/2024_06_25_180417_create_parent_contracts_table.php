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
        Schema::create('parent_contracts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('age')->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('email')->nullable();
            $table->string('id_number')->nullable();
            $table->string('id_end')->nullable();
            $table->string('accept_terms')->nullable();
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
        Schema::dropIfExists('parent_contracts');
    }
};
