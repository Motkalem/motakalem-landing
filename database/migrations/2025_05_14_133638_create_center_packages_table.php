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
        Schema::create('center_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->double('number_of_months')->default(1);
            $table->double('total')->nullable();

            $table->double( 'first_inst')->default(0.0);
            $table->float('second_inst')->default(0.0);
            $table->float('third_inst')->default(0.0);
            $table->float('fourth_inst')->default(0.0);
            $table->float('fifth_inst')->default(0.0);

            $table->date('starts_date')->nullable();
            $table->date('ends_date')->nullable();
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
        Schema::dropIfExists('center_packages');
    }
};
