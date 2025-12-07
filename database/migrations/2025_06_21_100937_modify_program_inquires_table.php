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
            $table->text('utm_source')->nullable()->after('source');
            $table->text('utm_medium')->nullable()->after('utm_source');
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

            $table->dropColumn(['utm_source', 'utm_medium']);
        });
    }
};
