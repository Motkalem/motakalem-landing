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
        Schema::table('installments', function (Blueprint $table) {

            $table->text('admin_ip')->after('installment_date')->nullable() ;
            $table->date('paid_at')->after('installment_date')->nullable() ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('installments', function (Blueprint $table) {

            $table->dropColumn('paid_at');
            $table->dropColumn('admin_ip');
        });
    }
};
