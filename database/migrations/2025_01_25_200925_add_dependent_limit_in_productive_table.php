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
           $table->bigInteger('limit_for_sale')->after('limit_for_request')->default(0);
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
            $table->dropColumn('limit_for_sale');
        });
    }
};
