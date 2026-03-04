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
        Schema::table('destruction_details', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->string('batch_number')->nullable()->after('amount');
            $table->double('productive_sale_price')->default(0)->after('batch_number');
            $table->double('productive_buy_price')->default(0)->after('productive_sale_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('destruction_details', function (Blueprint $table) {
            $table->double('price')->default(0);
            $table->dropColumn(['batch_number', 'productive_sale_price', 'productive_buy_price']);
        });
    }
};
