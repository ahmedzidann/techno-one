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
        Schema::table('productive', function (Blueprint $table) {
            //
            $table->integer('limit_for_request')->default(0)->after('num_pieces_in_package');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('productive', function (Blueprint $table) {
            $table->dropColumn('limit_for_request');
        });
    }
};
