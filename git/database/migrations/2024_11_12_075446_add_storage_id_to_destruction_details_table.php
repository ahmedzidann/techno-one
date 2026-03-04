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
        Schema::table('destruction_details', function (Blueprint $table) {
            $table->unsignedBigInteger('storage_id')->nullable()->after('id');
            $table->foreign('storage_id')->references('id')->on('storages')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('destruction_details', function (Blueprint $table) {
            $table->dropColumn('storage_id');
        });
    }
};
