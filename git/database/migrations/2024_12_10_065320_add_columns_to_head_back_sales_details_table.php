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
        Schema::table('head_back_sales_details', function (Blueprint $table) {
            $table->unsignedBigInteger('sales_id')->nullable()->after('head_back_sales_id');
            $table->foreign('sales_id')->on('sales')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->double('bouns')->default(0)->after('productive_sale_price');
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
        Schema::table('head_back_sales_details', function (Blueprint $table) {
            $table->dropColumn(['sales_id', 'bouns', 'discount_percentage', 'batch_number']);
        });
    }
};
