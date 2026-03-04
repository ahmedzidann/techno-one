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
        Schema::create('purchases_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchases_id')->nullable()->index('purchases_details_purchases_id_foreign');
            $table->unsignedBigInteger('productive_id')->nullable()->index('purchases_details_productive_id_foreign');
            $table->string('productive_code')->nullable();
            $table->double('productive_buy_price')->default(0);
            $table->double('amount')->nullable();
            $table->double('total')->nullable();
            $table->enum('type', ['department', 'wholesale'])->default('wholesale');
            $table->double('all_pieces')->default(0);
            $table->date('date')->nullable();
            $table->unsignedBigInteger('publisher')->nullable()->index('purchases_details_publisher_foreign');
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
        Schema::dropIfExists('purchases_details');
    }
};
