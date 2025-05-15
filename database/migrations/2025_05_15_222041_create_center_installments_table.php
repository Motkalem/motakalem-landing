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
        Schema::create('center_installments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('installment_payment_id');
            $table->decimal('installment_amount', 10, 2);
            $table->date('installment_date');
            $table->date('paid_at')->nullable() ;
            $table->text('admin_ip')->nullable() ;
            $table->boolean('is_paid')->default(false);
            $table->timestamps();

            $table->foreign('installment_payment_id')
                ->references('id')
                ->on('center_installment_payments')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('center_installments', function (Blueprint $table) {

            $table->dropColumn('paid_at');
            $table->dropColumn('admin_ip');
        });
    }
};
