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
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('code')->unique();
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('governorate_id')->nullable()->index('clients_governorate_id_foreign');
            $table->unsignedBigInteger('city_id')->nullable()->index('clients_city_id_foreign');
            $table->string('address', 500)->nullable();
            $table->double('previous_indebtedness')->default(0);
            $table->timestamps();
            $table->unsignedBigInteger('publisher')->nullable()->index('clients_publisher_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
};
