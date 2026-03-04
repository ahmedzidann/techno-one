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
        Schema::table('purchases_details', function (Blueprint $table) {
            $table->double('tax')->default(0)->after('amount');
            $table->double('one_buy_price')->default(0)->after('tax');
        });
        Schema::table('sales_details', function (Blueprint $table) {
            $table->renameColumn('productive_sale_price', 'one_sell_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases_details', function (Blueprint $table) {
            $table->dropColumn(['tax', 'one_buy_price']);
        });
        Schema::table('sales_details', function (Blueprint $table) {
            $table->renameColumn('one_sell_price', 'productive_sale_price');
        });
    }
};
