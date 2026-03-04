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
        Schema::create('head_back_sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sales_number')->nullable();
            $table->date('sales_date')->nullable();
            $table->unsignedBigInteger('storage_id')->nullable()->index('head_back_sales_storage_id_foreign');
            $table->enum('pay_method', ['cash', 'debit'])->nullable();
            $table->unsignedBigInteger('client_id')->nullable()->index('head_back_sales_client_id_foreign');
            $table->string('fatora_number')->nullable();
            $table->double('total')->default(0);
            $table->date('date')->nullable();
            $table->unsignedBigInteger('publisher')->nullable()->index('head_back_sales_publisher_foreign');
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
        Schema::dropIfExists('head_back_sales');
    }
};
