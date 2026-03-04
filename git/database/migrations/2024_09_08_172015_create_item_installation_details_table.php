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
        Schema::create('item_installation_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('item_installation_id')->nullable()->index('item_installation_details_item_installation_id_foreign');
            $table->unsignedBigInteger('main_productive_id')->nullable()->index('item_installation_details_main_productive_id_foreign');
            $table->unsignedBigInteger('productive_id')->nullable()->index('item_installation_details_productive_id_foreign');
            $table->string('productive_code')->nullable();
            $table->double('productive_price')->nullable();
            $table->double('amount')->nullable();
            $table->date('date')->nullable();
            $table->unsignedBigInteger('publisher')->nullable()->index('item_installation_details_publisher_foreign');
            $table->string('month', 20)->nullable();
            $table->year('year')->nullable();
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
        Schema::dropIfExists('item_installation_details');
    }
};
