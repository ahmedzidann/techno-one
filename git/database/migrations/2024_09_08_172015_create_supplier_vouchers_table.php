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
        Schema::create('supplier_vouchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('voucher_date')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable()->index('supplier_vouchers_supplier_id_foreign');
            $table->double('paid')->nullable();
            $table->date('date')->nullable();
            $table->unsignedBigInteger('publisher')->nullable()->index('supplier_vouchers_publisher_foreign');
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
        Schema::dropIfExists('supplier_vouchers');
    }
};
