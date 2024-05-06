<?php

use App\Models\ClientPayOrder;
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
        Schema::table('client_pay_orders', function (Blueprint $table) {

            $table->string('payment_type')->after('email')->default(ClientPayOrder::ONE_TIME);
        });

        Schema::table('client_pay_orders', function (Blueprint $table) {

            $table->double('total_payment_amount')->after('payment_type')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_pay_orders', function (Blueprint $table) {
            $table->dropColumn('payment_type');
            $table->dropColumn('total_payment_amount');
        });
    }
};
