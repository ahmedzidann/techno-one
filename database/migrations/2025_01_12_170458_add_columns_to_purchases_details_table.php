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
        Schema::table('purchases_details', function (Blueprint $table) {
            $table->double('first_discount')->default(0)->after('discount_percentage');
            $table->double('second_discount')->default(0)->after('first_discount');
            $table->double('likely_discount')->default(0)->after('second_discount');
        });
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('fatora_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases_details', function (Blueprint $table) {
            $table->dropColumn(['first_discount', 'second_discount', 'likely_discount']);
        });
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('fatora_number')->nullable();
        });
    }
};
