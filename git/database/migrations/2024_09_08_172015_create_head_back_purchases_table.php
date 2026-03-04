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
        Schema::create('head_back_purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('purchases_number')->nullable();
            $table->date('purchases_date')->nullable();
            $table->unsignedBigInteger('storage_id')->nullable()->index('head_back_purchases_storage_id_foreign');
            $table->enum('pay_method', ['cash', 'debit'])->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable()->index('head_back_purchases_supplier_id_foreign');
            $table->string('fatora_number')->nullable();
            $table->string('supplier_fatora_number')->nullable();
            $table->double('total')->default(0);
            $table->date('date')->nullable();
            $table->unsignedBigInteger('publisher')->nullable()->index('head_back_purchases_publisher_foreign');
            $table->string('month', 20)->nullable();
            $table->year('year')->nullable();
            $table->timestamps();
            $table->double('paid')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('head_back_purchases');
    }
};
