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
        Schema::table('program_inquiries', function (Blueprint $table) {
            $table->json('transaction_data')->after('name')->nullable();
            $table->boolean('is_paid')->after('name')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('program_inquiries', function (Blueprint $table) {

            $table->dropColumn('transaction_data');
            $table->dropColumn('is_paid');
        });
    }
};
