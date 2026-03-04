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
        Schema::table('productive', function (Blueprint $table) {
            $table->unsignedBigInteger('zones_setting_id')->nullable()->after('company_id');
            $table->foreign('zones_setting_id')->on('zones_settings')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('productive', function (Blueprint $table) {
           $table->dropColumn('zones_setting_id');
        });
    }
};
