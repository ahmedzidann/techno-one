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
        Schema::create('item_installations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('install_date')->nullable();
            $table->unsignedBigInteger('productive_id')->nullable()->index('item_installations_productive_id_foreign');
            $table->date('date')->nullable();
            $table->unsignedBigInteger('publisher')->nullable()->index('item_installations_publisher_foreign');
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
        Schema::dropIfExists('item_installations');
    }
};
