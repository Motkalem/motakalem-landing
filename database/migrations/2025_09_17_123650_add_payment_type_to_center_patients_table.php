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
        Schema::table('center_patients', function (Blueprint $table) {
            $table->string('payment_type')->default('installment')->after('city');
            $table->foreignId('center_package_id')->nullable()->constrained('center_packages')->after('payment_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('center_patients', function (Blueprint $table) {
            $table->dropForeign(['center_package_id']);
            $table->dropColumn(['payment_type', 'center_package_id']);
        });
    }
};
