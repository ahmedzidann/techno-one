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
        Schema::table('head_back_purchases', function (Blueprint $table) {
            $table->unsignedBigInteger('purchase_id')->nullable()->after('purchases_number');
            $table->foreign('purchase_id')->on('purchases')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->json('products_ids')->nullable()->after('purchases_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('head_back_purchases', function (Blueprint $table) {
            $table->dropColumn(['purchase_id', 'products_ids']);
        });
    }
};
