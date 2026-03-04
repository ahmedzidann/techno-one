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
        Schema::create('client_payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->tinyInteger('payment_category')
                ->comment('[EVERY_MONTH => 1,EVERY_15_DAYS => 2,EVERY_WEEK => 3,ON_DELIVERED => 4]');
            $table->tinyInteger('month');
            $table->tinyInteger('from_day');
            $table->tinyInteger('to_day');
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
        Schema::dropIfExists('client_payment_settings');
    }
};
