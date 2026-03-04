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
            $table->double('bouns')->default(0)->after('productive_buy_price');
            $table->double('discount_percentage')->default(0)->after('bouns');
            $table->string('batch_number')->default(0)->after('discount_percentage');
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
            $table->dropColumn(['bouns', 'discount_percentage', 'batch_number']);
        });
    }
};
