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

            $table->renameColumn('installment_value', 'first_inst')->default(0.0)->change();
        });

        Schema::table('packages', function (Blueprint $table) {

            $table->float('fifth_inst')->default(0.0)->after('first_inst');
            $table->float('fourth_inst')->default(0.0)->after('first_inst');
            $table->float('third_inst')->default(0.0)->after('first_inst');
            $table->float('second_inst')->default(0.0)->after('first_inst');
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


            $table->renameColumn( 'first_inst' ,'installment_value');
            $table->dropColumn('fourth_inst');
            $table->dropColumn('third_inst');
            $table->dropColumn('second_inst');
            $table->dropColumn('fifth_inst');
        });
    }
};
