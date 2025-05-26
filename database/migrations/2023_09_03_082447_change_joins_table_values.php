<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('joins', function (Blueprint $table) {
            $table->float('phone')->change();
            $table->float('another_phone')->change();
        });
    }

    public function down()
    {
        Schema::table('joins', function (Blueprint $table) {
            $table->integer('phone')->change();
            $table->integer('another_phone')->change();
        });
    }
};
