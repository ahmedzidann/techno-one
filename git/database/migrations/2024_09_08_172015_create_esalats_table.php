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
        Schema::create('esalats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date_esal')->nullable();
            $table->unsignedBigInteger('client_id')->nullable()->index('esalats_client_id_foreign');
            $table->double('paid')->nullable();
            $table->string('notes', 500)->nullable();
            $table->date('date')->nullable();
            $table->unsignedBigInteger('publisher')->nullable()->index('esalats_publisher_foreign');
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
        Schema::dropIfExists('esalats');
    }
};
