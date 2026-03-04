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
            $table->dropColumn('one_sell_price');
            $table->dropColumn('packet_sell_price');
            $table->dropColumn('one_buy_price');
            $table->dropColumn('packet_buy_price');
            $table->dropColumn('product_type');
            $table->double('audience_price')->default(0)->after('name');
            $table->unsignedBigInteger('shape_id')->nullable()->after('company_id');
            $table->foreign('shape_id', 'productive_shape_id_foreign')->on('shapes')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
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
            $table->dropForeign('productive_shape_id_foreign');
            $table->dropColumn([
                'audience_price',
                'shape_id',
            ]);
            $table->double('one_sell_price')->default(0);
            $table->double('packet_sell_price')->default(0);
            $table->double('one_buy_price')->default(0);
            $table->double('packet_buy_price')->default(0);
            $table->double('product_type')->default(0);
        });
    }
};
