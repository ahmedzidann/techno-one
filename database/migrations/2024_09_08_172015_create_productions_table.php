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
        Schema::create('productions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('production_date')->nullable();
            $table->unsignedBigInteger('storage_id')->nullable()->index('productions_storage_id_foreign');
            $table->date('date')->nullable();
            $table->unsignedBigInteger('publisher')->nullable()->index('productions_publisher_foreign');
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
        Schema::dropIfExists('productions');
    }
};
