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
        Schema::table('medical_inquiries', function (Blueprint $table)
        {
            $table->string('email')->after('mobile_number')->nullable();
            $table->string('id_end_date')->after('mobile_number')->nullable();
            $table->string('id_number')->after('mobile_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medical_inquiries', function (Blueprint $table)
        {
            $table->dropColumn('email');
            $table->dropColumn('id_end_date');
            $table->dropColumn('id_number');
        });
    }
};
