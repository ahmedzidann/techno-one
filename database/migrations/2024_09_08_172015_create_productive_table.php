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
        Schema::create('productive', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->enum('product_type', ['tam', 'kham'])->nullable();
            $table->double('one_buy_price')->default(0);
            $table->double('packet_buy_price')->default(0);
            $table->double('one_sell_price')->default(0);
            $table->double('packet_sell_price')->default(0);
            $table->double('num_pieces_in_package')->default(0);
            $table->unsignedBigInteger('unit_id')->nullable()->index('productive_unit_id_foreign');
            $table->unsignedBigInteger('category_id')->nullable()->index('productive_category_id_foreign');
            $table->unsignedBigInteger('publisher')->nullable()->index('productive_publisher_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productive');
    }
};
