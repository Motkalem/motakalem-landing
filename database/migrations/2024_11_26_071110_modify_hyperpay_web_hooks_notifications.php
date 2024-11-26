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
        Schema::table('hyperpay_web_hooks_notifications', function (Blueprint $table) {
            $table->boolean('student_notified')->after('payload')->default(false);
            $table->boolean('admin_notified')->after('payload')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hyperpay_web_hooks_notifications', function (Blueprint $table) {

            $table->dropColumn('student_notified')->default(false);
            $table->dropColumn('admin_notified')->default(false);
        });
    }
};
