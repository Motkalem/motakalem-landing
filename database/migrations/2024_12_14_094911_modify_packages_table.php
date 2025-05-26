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

        Schema::table('packages', function (Blueprint $table) {

            $table->date('starts_date')->after('total')->nullable();
        });
        Schema::table('packages', function (Blueprint $table) {

            $table->date('ends_date')->after('starts_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('starts_date');
            $table->dropColumn('ends_date');
        });
    }
};
