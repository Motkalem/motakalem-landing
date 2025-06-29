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
        Schema::table('installment_payments', function (Blueprint $table) {

            $table->text('recurring_agreement_id')->after('registration_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('installment_payments', function (Blueprint $table) {

            $table->dropColumn('recurring_agreement_id');
        });
    }
};
