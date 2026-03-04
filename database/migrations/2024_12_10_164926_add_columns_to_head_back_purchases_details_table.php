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
        Schema::table('head_back_purchases_details', function (Blueprint $table) {
            $table->unsignedBigInteger('purchase_id')->nullable()->after('head_back_purchases_id');
            $table->foreign('purchase_id')->on('purchases')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::table('head_back_purchases_details', function (Blueprint $table) {
            $table->dropColumn(['purchase_id', 'bouns', 'discount_percentage', 'batch_number']);
        });
    }
};
