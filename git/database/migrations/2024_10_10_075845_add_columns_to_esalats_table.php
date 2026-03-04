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
        Schema::table('esalats', function (Blueprint $table) {
            $table->tinyInteger('payment_category')
                ->comment('[EVERY_MONTH => 1,EVERY_15_DAYS => 2,EVERY_WEEK => 3,ON_DELIVERED => 4]')->after('id');
            $table->unsignedBigInteger('client_payment_setting_id')->nullable()->after('id');
            $table->foreign('client_payment_setting_id')->references('id')->on('client_payment_settings')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('esalats', function (Blueprint $table) {
            $table->dropColumn(columns: ['payment_category', 'client_payment_setting_id']);
        });
    }
};
