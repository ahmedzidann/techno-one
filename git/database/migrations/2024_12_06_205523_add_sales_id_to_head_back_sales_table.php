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
        Schema::table('head_back_sales', function (Blueprint $table) {
            $table->unsignedBigInteger('sales_id')->nullable()->after('sales_number');
            $table->foreign('sales_id')->on('sales')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('head_back_sales', function (Blueprint $table) {
            $table->dropColumn('sales_id');
        });
    }
};
